<?php

namespace App\Http\Controllers;

use App\Locker;
use App\LockerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LockerController extends Controller
{
    /**
     * @var LockerLog
     */
    private $lockerLog;

    public function __construct(LockerLog $lockerLog)
    {
        $this->lockerLog = $lockerLog;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lockers = Locker::all();

        return view('lockers.index', ['lockers' => $lockers]);
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
        DB::transaction(function () use ($request, $locker) {
            $this->backup($locker);

            $inputs = $request->only('username', 'from');

            if(!$inputs['username']) {
                $inputs['from'] = null;
                $inputs['to'] = null;
            } else {
                $inputs['to'] = $locker->calcLastday($request->from);
            }

            $locker->update($inputs);
        });

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
        //
    }

    private function backup(Locker $locker)
    {
        if($locker->username) {
            $before = $locker->toArray();
            unset($before['created_at']);
            unset($before['updated_at']);

            $this->lockerLog->create($before);
        }
    }
}
