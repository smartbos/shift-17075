<?php

namespace App\Http\Controllers;

use App\Reservation;
use Illuminate\Http\Request;

class TwilioController extends Controller
{
    public function store(Request $request, Reservation $reservation)
    {
        $reservation->storeUsingSms($request->input('Body'));
    }
}
