<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['name', 'phone','room', 'from', 'to'];

    protected $dates = ['from', 'to','created_at', 'updated_at'];

    public function toSendSms()
    {
        $now = Carbon::now();

        if($now->minute % 30 == 0) {
            $from = Carbon::now()->addHour(1)->format('Y-m-d H:i:00');
            return Reservation::where('from', '=', $from)
                ->where('name', '강신구')
                ->get();
        } else {
            $from = Carbon::now()->addMinutes(10)->format('Y-m-d H:i:00');
            return Reservation::where('from', '=', $from)->get();
        }
    }
}
