<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Locker;
use App\Reservation;
use App\Roomcode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Customer $customer
     * @param Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function index(Roomcode $roomcode, Locker $locker, Reservation $reservation)
    {
        $todayCodes = $roomcode->today()->orderBy('room_type')->get();

        $expiredLockers = $locker->expired()->orderBy('num')->get();

        $todayReservations = $reservation->with('branch')->today()->orderBy('from')->get();
        $todayReservationGroups = $todayReservations->groupBy(function ($item, $key) {
            return $item->branch->name;
        });

        $lastNaverReservationFileUploadedAt = $reservation->getLastNaverReservationFileUploadedAt();

        return view('home', [
            'roomcodes' => $todayCodes,
            'expiredLockers' => $expiredLockers,
            'todayReservationGroups' => $todayReservationGroups,
            'lastNaverReservationFileUploadedAt' => $lastNaverReservationFileUploadedAt,
        ]);
    }
}
