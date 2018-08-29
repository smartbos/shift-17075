<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Reservation;
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
    public function index(Customer $customer)
    {
        $reservations = Reservation::where('from', '>', Carbon::today())->paginate(30);

        $unregisteredCustomers = $customer->getUnregistered();

        return view('home', [
            'reservations' => $reservations,
            'unregisteredCustomers' => $unregisteredCustomers
        ]);
    }
}
