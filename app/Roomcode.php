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
}
