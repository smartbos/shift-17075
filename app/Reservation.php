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
        $after10Min = Carbon::now()->addMinutes(10)->format('Y-m-d H:i:00');
        $after1hour = Carbon::now()->addHour(1)->format('Y-m-d H:i:00');

        $normalReservations = Reservation::where('from', '=', $after10Min)->get();
        $kangReservation = Reservation::where('from', '=', $after1hour)
            ->where('name', '강신구')
            ->get();

        $merged = new Collection();
        $merged->merge($normalReservations)->merge($kangReservation);

        return $merged;
    }
}
