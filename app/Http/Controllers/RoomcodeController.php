<?php

namespace App\Http\Controllers;

use App\Roomcode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roomcodes = Roomcode::where('date','>=', Carbon::today())->get();

        return view('roomcodes.index', ['roomcodes' => $roomcodes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Roomcode $roomcode)
    {
        if($request->hasFile('file')) {
            $roomcode->storeUsingFile($request->file('file'));
        } else {
            $roomcode->create($request->all());
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Roomcode  $roomcode
     * @return \Illuminate\Http\Response
     */
    public function show(Roomcode $roomcode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Roomcode  $roomcode
     * @return \Illuminate\Http\Response
     */
    public function edit(Roomcode $roomcode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Roomcode  $roomcode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Roomcode $roomcode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Roomcode  $roomcode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Roomcode $roomcode)
    {
        //
    }
}
