<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'instruction_link'];

    protected $roomTypes = [
        '세미나실 3인실',
        '세미나실 6인실',
        '세미나실 8인실',
        '세미나실 A',
        '세미나실 B'
    ];

    public function getRoomTypes()
    {
        return $this->roomTypes;
    }
}
