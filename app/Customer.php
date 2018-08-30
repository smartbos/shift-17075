<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Rap2hpoutre\FastExcel\FastExcel;

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

    public function storeUsingFile($file)
    {
        $rows = (new FastExcel())->import($file);

        foreach ($rows as $row) {
            try {
                $inputs = [
                    'name' => $row['이름'],
                    'phone' => str_replace('-', '', $row['휴대폰']),
                    'phone_last' => substr($row['휴대폰'], -4)
                ];

                $this->create($inputs);
            } catch (\Exception $e) {

            }
        }
    }
}
