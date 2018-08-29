<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'phone_last'];

    public function getUnregistered()
    {
        $unRegisteredCustomers = [];

        $reservations = Reservation::where('from', '>', Carbon::today())->get();

        foreach ($reservations as $reservation) {
            if ( ! Customer::where('name', $reservation->name)
                ->where('phone_last', $reservation->phone)
                ->exists()) {
                $unRegisteredCustomers[] = $reservation;
            }
        }

        return $unRegisteredCustomers;
    }
}
