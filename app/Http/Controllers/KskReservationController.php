<?php

namespace App\Http\Controllers;

use App\Reservation;
use Illuminate\Http\Request;

class KskReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::ksk()->fromToday()->orderBy('from');

        return view('ksk.index', ['reservations' => $reservations]);
    }

    public function create()
    {

    }

    public function store()
    {

    }

    public function destroy()
    {

    }
}
