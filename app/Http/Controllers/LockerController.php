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
        DB::transaction(function () use ($request, $locker) {
            $this->backup($locker);

            $inputs = $request->only('username', 'from' , 'password');

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
        DB::transaction(function() use ($locker){
            $this->backup($locker);

            $locker->username = null;
            $locker->from = null;
            $locker->to = null;
            $locker->save();
        });

        return back();
    }

    private function backup(Locker $locker)
    {
        if($locker->username) {
            $before = $locker->toArray();
            $before['locker_id'] = $before['id'];
            unset($before['id']);
            unset($before['created_at']);
            unset($before['updated_at']);

            $this->lockerLog->create($before);
        }
    }
}
