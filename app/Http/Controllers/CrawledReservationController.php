<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CrawledReservationController extends Controller
{
    public function store(Request $request)
    {
        /*
         * state
         * name
         * phone
         * date
         * room
         * book_number 예약번호
         *
         * find
         * state == 확정 => insert
         * state != 확정 => delete
         */
        $inputs = $request->all();

        $reservation = Reservation::where($whereData = $this->makeWhereData($inputs))->first();

        if(!$reservation) {
            if($inputs['state'] == '확정') {
                Reservation::create($whereData);
                return 'created';
            }
        } else {
            if($inputs['state'] == '취소' && $reservation->book_number == $inputs['book_number']) {
                $reservation->delete();
                return 'deleted';
            }
        }

        $customer = Customer::whereName($inputs['name'])
            ->wherePhone(str_replace('-','',$inputs['phone']))
            ->first();

        if(!$customer) {
            Customer::create([
                'name' => $inputs['name'],
                'phone' => str_replace('-', '', $inputs['phone']),
                'phone_last' => substr($inputs['phone'], -4)
            ]);
        }
    }

    public function makeWhereData($inputs)
    {
        $fromTo = $this->parseDate($inputs['date']);

        $return = [
            'name' => $inputs['name'],
            'phone' => $this->lastFourDigitsOfPhoneNumber($inputs['phone']),
            'room' => $inputs['room'],
            'from' => $fromTo['from'],
            'to' => $fromTo['to'],
            'branch_id' => $inputs['branch_id'],
            'book_number' => $inputs['book_number']
        ];

        return $return;
    }

    public function lastFourDigitsOfPhoneNumber($phone)
    {
        $arr = explode('-', $phone);
        return $arr[2];
    }

    public function parseDate($date)
    {
        $dateString = mb_strcut($date, 0, mb_strpos($date, '('));

        $beforeOrAfterNoon = mb_strpos($date, '오전') ? 'before' : 'after';

        $timeString = substr($date, strpos($date, '오')+7);

        $timeStringArr = explode('~', $timeString);
        $timeStringArr = array_map(function($item) {
            return trim($item);
        }, $timeStringArr);

        $startTimeString = "{$dateString} {$timeStringArr[0]}:00";
        $endTimeString = "{$dateString} {$timeStringArr[1]}:00";

        $startCarbon = Carbon::createFromFormat('y. n. j. G:i:s', $startTimeString);
        $endCarbon = Carbon::createFromFormat('y. n. j. G:i:s', $endTimeString);

        if($beforeOrAfterNoon == 'after') {
            $startCarbon->addHours(12);
            $endCarbon->addHours(12);
        }

        if($startCarbon->greaterThan($endCarbon)) {
            $endCarbon->addHours(12);
        }

        return [
            'from' => $startCarbon,
            'to' => $endCarbon
        ];
    }
}
