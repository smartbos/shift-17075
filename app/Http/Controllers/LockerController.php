<?php

namespace App\Http\Controllers;

use App\Locker;
use App\LockerLog;
use App\Services\LockerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LockerController extends Controller
{
    /**
     * @var LockerService
     */
    private $lockerService;

    public function __construct(LockerService $lockerService)
    {
        $this->lockerService = $lockerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Locker $locker
     * @return \Illuminate\Http\Response
     */
    public function index(Locker $locker)
    {
        $lockers = $locker->all();

        $expiredLockers = $locker->expired()->orderBy('num')->get();

        return view('lockers.index', ['lockers' => $lockers, 'expiredLockers' => $expiredLockers]);
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
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Locker  $locker
     * @return \Illuminate\Http\Response
     */
    public function show(Locker $locker)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Locker  $locker
     * @return \Illuminate\Http\Response
     */
    public function edit(Locker $locker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Locker  $locker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Locker $locker)
    {
        $this->lockerService->update($request, $locker);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Locker  $locker
     * @return \Illuminate\Http\Response
     */
    public function destroy(Locker $locker)
    {
        DB::transaction(function() use ($locker){
            $this->lockerService->backup($locker);

            $locker->username = null;
            $locker->from = null;
            $locker->to = null;
            $locker->save();
        });

        return back();
    }
}
