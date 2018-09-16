<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    protected $fillable = ['num', 'username', 'from', 'to', 'password'];

    public function calcLastday($from)
    {
        $from = str_replace('-', '', $from);
        return Carbon::createFromFormat('Ymd', $from)->addWeek(4)->format('Ymd');
    }

    public function scopeExpired($query)
    {
        return $query->where('to', '<=', Carbon::today()->format('Ymd'));
    }
}
