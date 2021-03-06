<?php

namespace App;

use Carbon\Carbon;
use http\Exception;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Database\Eloquent\Collection;

class Reservation extends Model
{
    protected $fillable = ['name', 'phone', 'room', 'from', 'to', 'branch_id'];

    protected $dates = ['from', 'to', 'created_at', 'updated_at'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopeToday($query)
    {
        return $query->whereBetween('from', [Carbon::today()->format('Y-m-d'), Carbon::today()->addDay()->format('Y-m-d')]);
    }

    public function toSendSms()
    {
        $now = Carbon::now();

        if ($now->minute % 30 == 0) {
            $from = Carbon::now()->addHour(1)->format('Y-m-d H:i:00');

            return $this->where('from', '=', $from)
                ->where('name', '강신구')
                ->get();
        } else {
            $from = Carbon::now()->addMinutes(10)->format('Y-m-d H:i:00');

            return $this->where('from', '=', $from)
                ->where('name', '!=', '강신구')
                ->get();
        }
    }

    public function storeUsingFile($file, $branch_id, $type = 'default')
    {
        if ($type == 'default') {
            $this->storeUsingNaverFile($file, $branch_id);
        } else {
            $this->storeUsingDriveFile($file);
        }
    }

    private function transform($row)
    {
        $result['room'] = $row['상품'];

        if ($row['상품'] == '세미나실 A' || $row['상품'] == '세미나실 B') {
            $result['branch_id'] = 2;
        } else {
            $result['branch_id'] = 1;
        }

        $result['name'] = $row['예약자'];

        $result['phone'] = mb_substr($row['전화번호'], -4, 4);
        $date = $fromDate = $toDate = mb_substr($row['이용일시'], 0, strpos($row['이용일시'], '('));
        $time = mb_substr($row['이용일시'], strpos($row['이용일시'], ')'));

        $timeArray = explode(' ', $time);
        $timeArray2 = explode('~', $timeArray[1]);
        $fromArray = explode(':', $timeArray2[0]);
        $toArray = explode(':', $timeArray2[1]);
        if ($timeArray[0] == '오후') { // 오후 12:00~2:00 | 11:00~12:00 |
            if ($fromArray[0] != 12) {
                $fromArray[0] = $fromArray[0] + 12; // 24 | 23
            }

            $toArray[0] = $toArray[0] + 12; // 14 | 24

            if ($toArray[0] == 24) {
                $toArray[0] = 0;
                $toDateCarbon = Carbon::createFromFormat('y. m. d.', $toDate);
                $toDate = $toDateCarbon->addDay(1)->format('y. m. d.');
            }
        } else {
            if ($fromArray[0] == 12) {
                $fromArray[0] = 0;
            }

            if ($toArray[0] < $fromArray[0]) {
                $toArray[0] = $toArray[0] + 12;
            }
        }

        $fromString = implode(':', $fromArray);
        $toString = implode(':', $toArray);

        //dd($date);
        $result['from'] = Carbon::createFromFormat('y. m. d. H:i', $fromDate . $fromString);
        $result['to'] = Carbon::createFromFormat('y. m. d. H:i', $toDate . $toString);

        return $result;
    }

    public function storeUsingSms($sms)
    {
        // 결제완료? 예약취소?
        $sms = explode("\n", $sms);

        if (Str::contains($sms[1], '예약취소')) {
            $name = trim($sms[2]);
            $startFrom = Carbon::now()->format('Y.') . trim($sms[3]);
            $startFrom = Carbon::createFromFormat('Y.m.d. H:i', $startFrom);

            $this->where('name', $name)
                ->where('from', $startFrom)
                ->delete();
        } elseif (Str::contains($sms[9], '결제완료')) {
            $data = [
                'name' => '',
                'phone' => '',
                'room' => '',
                'from' => '',
                'to' => '',
                'branch_id' => '',
            ];

            $nameInfo = $sms[5];
            $nameArray = explode(':', $nameInfo);

            $data['name'] = trim($nameArray[1]);
            $phone = str_replace('-', '', filter_var($sms[6], FILTER_SANITIZE_NUMBER_INT));
            $phoneLast = mb_substr($phone, -4);

            if (Customer::where('phone', $phone)->doesntExist()) {
                Customer::create([
                    'name' => trim($nameArray[1]),
                    'phone' => $phone,
                    'phone_last' => $phoneLast,
                ]);
            }

            $data['phone'] = $phoneLast;
            $roomInfo = $sms[7];
            $roomArray = explode(':', $roomInfo);

            $data['room'] = trim($roomArray[1]);

            $time = mb_substr($sms[8], 8);

            $toDate = $date = mb_substr($time, 0, 10);

            $timeArray = explode('~', $time);

            $fromInfo = $timeArray[0];
            $toInfo = $timeArray[1];

            $fromArray = explode(' ', mb_substr($fromInfo, mb_strpos($fromInfo, '오')));
            $fromTimeArray = explode(':', $fromArray[1]);

            if ($fromArray[0] == '오후' && $fromTimeArray[0] != '12') {
                $data['from'] = Carbon::createFromFormat('Y.m.d G:i', $date . ' ' . ($fromTimeArray[0] + 12) . ':' . $fromTimeArray[1]);
            } else {
                $data['from'] = Carbon::createFromFormat('Y.m.d G:i', $date . ' ' . $fromTimeArray[0] . ':' . $fromTimeArray[1]);
            }

            $toArray = explode(' ', $toInfo);
            $toTimeArray = explode(':', $toArray[1]);
            if ($toArray[0] == '오후') {
                $data['to'] = Carbon::createFromFormat('Y.m.d H:i', $date . ' ' . ($toTimeArray[0] + 12) . ':' . filter_var($toTimeArray[1], FILTER_SANITIZE_NUMBER_INT));
            } else {
                if ($toTimeArray[0] == 0) {
                    $toDateCarbon = Carbon::createFromFormat('Y.m.d', $date);
                    $toDate = $toDateCarbon->addDay(1)->format('Y.m.d');
                }
                $data['to'] = Carbon::createFromFormat('Y.m.d G:i', $toDate . ' ' . $toTimeArray[0] . ':' . filter_var($toTimeArray[1], FILTER_SANITIZE_NUMBER_INT));
            }

            $branchArray = explode(',', $sms[2]);
            if ($branchArray[0] == '일공공') {
                $data['branch_id'] = 1;
            } else {
                $data['branch_id'] = 2;
            }

            $this->create($data);
        }
    }

    /**
     * @param $file
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    private function storeUsingNaverFile($file, $branch_id): void
    {
        // 의미없는 첫 두 줄을 제거하고 파일을 다시 만듦
        $sheet = IOFactory::load($file);
        $activeSheet = $sheet->getActiveSheet();
        $activeSheet->removeRow(1, 2);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($sheet, 'Xlsx');
        $writer->save(storage_path('temp.xlsx'));

        $rows = (new FastExcel())->import(storage_path('temp.xlsx'));

        // insert
        foreach ($rows as $row) {
            if ($row['상태']) {
                $insertData = $this->transform($row);
                $insertData['branch_id'] = $branch_id;
                if ($row['상태'] == '확정') {
                    try {
                        $this->create($insertData);
                    } catch (\PDOException $e) {
                    }
                }

                if ($row['상태'] == '취소') {
                    $reservation = $this->where($insertData)->first();
                    if ($reservation) {
                        $reservation->delete();
                    }
                }
            }
        }

        $this->updateLastNaverReservationFileUploadedAtRedis($branch_id);
    }

    private function updateLastNaverReservationFileUploadedAtRedis($branch_id)
    {
        $lastNaverReservationFileUploadedAt = json_decode(Redis::get('lastNaverReservationFileUploadedAt'));

        if ($lastNaverReservationFileUploadedAt) {
            $lastNaverReservationFileUploadedAt->$branch_id = Carbon::now()->format('Y-m-d H:i');
        } else {
            $lastNaverReservationFileUploadedAt = [
                $branch_id => Carbon::now()->format('Y-m-d H:i')
            ];
        }

        Redis::set('lastNaverReservationFileUploadedAt', json_encode($lastNaverReservationFileUploadedAt));
    }

    /**
     * @param $file
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Reader\Exception\ReaderNotOpenedException
     */
    private function storeUsingDriveFile($file): void
    {
        $rows = (new FastExcel())->import($file);

        // insert
        foreach ($rows as $row) {
            $fromTime = $row['시작시각'];
            if ($fromTime instanceof \DateTime) {
                $fromTime = $fromTime->format('Hi');
            }

            $toTime = $row['종료시각'];
            if ($toTime instanceof \DateTime) {
                $toTime = $toTime->format('Hi');
            }

            try {
                $this->create([
                    'name' => '강신구',
                    'phone' => '1024',
                    'room' => "세미나실 {$row['방']}인실",
                    'from' => Carbon::createFromFormat('YmdHi', $row['날짜'] . $fromTime),
                    'to' => Carbon::createFromFormat('YmdHi', $row['날짜'] . $toTime),
                ]);
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        }
    }

    /**
     * 강신구 예약 내역만 조회.
     * @param $query
     * @return mixed
     */
    public function scopeKsk($query)
    {
        return $query->where('name', '강신구');
    }

    public function scopeFromToday($query)
    {
        return $query->where('from', '>=', Carbon::today());
    }

    public function getLastNaverReservationFileUploadedAt()
    {
        $result = [];

        $lastNaverReservationFileUploadedAt = json_decode(Redis::get('lastNaverReservationFileUploadedAt'));

        if ($lastNaverReservationFileUploadedAt) {
            foreach ($lastNaverReservationFileUploadedAt as $key => $value) {
                $result[] = [
                    'branch' => Branch::find($key),
                    'uploadedAt' => $value,
                    'today' => Carbon::createFromFormat('Y-m-d H:i', $value)->greaterThan(Carbon::today()),
                ];
            }
        }

        return $result;
    }

    public function storeUsingForm($inputs)
    {
        $this->create($inputs);
    }
}
