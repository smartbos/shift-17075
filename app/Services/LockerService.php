<?php
/**
 * Created by PhpStorm.
 * User: hyunseoklee
 * Date: 02/07/2019
 * Time: 7:48 PM
 */

namespace App\Services;


use App\Locker;
use App\LockerLog;
use Illuminate\Support\Facades\DB;

class LockerService
{
    public function update($request, $locker)
    {
        DB::transaction(function () use ($request, $locker) {
            $this->backup($locker);

            $inputs = $this->buildInputsForUpdate($request, $locker);

            $locker->update($inputs);
        });
    }

    public function backup(Locker $locker)
    {
        if($locker->username) {
            $before = $locker->toArray();
            $before['locker_id'] = $before['id'];
            unset($before['id']);
            unset($before['created_at']);
            unset($before['updated_at']);

            LockerLog::create($before);
        }
    }

    /**
     * @param $request
     * @param $locker
     * @return mixed
     */
    function buildInputsForUpdate($request, $locker)
    {
        $inputs = $request->only('username', 'from', 'password');

        if (!$inputs['username']) {
            $inputs['from'] = null;
            $inputs['to'] = null;
        } else {
            $inputs['to'] = $locker->calcLastday($request->from);
        }
        return $inputs;
    }
}
