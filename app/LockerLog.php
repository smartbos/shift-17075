<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LockerLog extends Model
{
    protected $fillable = ['id', 'num', 'username', 'from', 'to', 'password'];
}
