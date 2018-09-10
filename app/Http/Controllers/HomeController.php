<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Reservation;
use App\Roomcode;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
    public function index(Roomcode $roomcode)
    {
        $todayCodes = $roomcode->today()->orderBy('room_type')->get();

        return view('home', [
            'roomcodes' => $todayCodes
        ]);
    }
}
