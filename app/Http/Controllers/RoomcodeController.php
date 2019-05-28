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
        $roomcodes = Roomcode::where('date','>=', Carbon::today())->orderBy('date')->orderBy('room_type')->get();
        $roomcodes->load('branch');

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
            if($request->input('room_type') === '세미나실 3인실') {
                $roomcode->createForAllRoomTypes($request->all(), 1);
            } elseif($request->input('room_type') === '세미나실 A'){
                $roomcode->createForAllRoomTypes($request->all(), 2);
            } else {
                $inputs = $request->all();
                $inputs['branch_id'] = 1;

                if($request->input('room_type') == '세미나실 A' || $request->input('room_type') == '세미나실 B') {
                    $inputs['branch_id'] = 2;
                }

                $roomcode->create($inputs);
            }

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
