<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Rap2hpoutre\FastExcel\FastExcel;

class Roomcode extends Model
{
    protected $fillable = ['date', 'code', 'room_type'];

    public function storeUsingFile($file)
    {
        $rows = (new FastExcel())->import($file);

        foreach ($rows as $row) {
            $code = substr($row['코드'], 0, 6);

            try {
                $this->create([
                    'date' => $row['날짜'],
                    'code' => $code,
                    'room_type' => $row['룸']
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
    public function createForAllRoomTypes($inputs)
    {
        $this->create($inputs);

        $inputs['room_type'] = 6;
        $this->create($inputs);

        $inputs['room_type'] = 8;
        $this->create($inputs);
    }
}
