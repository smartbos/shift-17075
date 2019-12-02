<?php

namespace App;

use Carbon\Carbon;
use mysql_xdevapi\Exception;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Database\Eloquent\Model;

class Roomcode extends Model
{
    protected $fillable = ['date', 'code', 'room_type', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function storeUsingFile($file)
    {
        $rows = (new FastExcel())->import($file);

        $this->validateRows($rows);

        foreach ($rows as $row) {
            $code = substr($row['코드'], 0, 6);

            try {
                $this->create([
                    'date' => $row['날짜'],
                    'code' => $code,
                    'room_type' => $row['룸'],
                    'branch_id' => $row['지점']
                ]);
            } catch (\Exception $e) {
            }
        }
    }

    public function scopeToday($query)
    {
        return $query->where('date', Carbon::today()->format('Y-m-d'));
    }

    /**
     * 모든 룸의 코드를 3인실 코드로 입력한다.
     * @param $inputs
     */
    public function createForAllRoomTypes($inputs, $branchId)
    {
        $inputs['branch_id'] = $branchId;

        $this->create($inputs);

        if ($inputs['room_type'] == '세미나실 3인실') {
            $inputs['room_type'] = '세미나실 6인실';
            $this->create($inputs);

            $inputs['room_type'] = '세미나실 8인실';
            $this->create($inputs);
        }

        if ($inputs['room_type'] == '세미나실 A') {
            $inputs['room_type'] = '세미나실 B';
            $this->create($inputs);
        }
    }

    private function validateRows(\Illuminate\Support\Collection $rows)
    {
        foreach($rows as $row) {
            if ( ! isset($row['지점']))
                throw new \Exception('No branch id');

            if ( ! is_int($row['지점']))
                throw new \Exception('Branch id must be int');

            if ($row['room_type'] == '세미나실 A' ||  $row['room_type'] == '세미나실 B') {
                if($row['지점'] == 1) {
                    throw new \Exception('Invalid branch id');
                }
            }
        }
    }
}
