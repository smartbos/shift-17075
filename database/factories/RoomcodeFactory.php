<?php

use Faker\Generator as Faker;

$factory->define(App\Roomcode::class, function (Faker $faker) {
    return [
        'date' => \Carbon\Carbon::now()->format('Ymd'),
        'code' => rand(000000, 235959),
        'room_type' => array_rand(['세미나실 3인실']),
        'branch_id'  => array_rand([1,2])
    ];
});
