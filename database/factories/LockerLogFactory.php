<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\LockerLog::class, function (Faker $faker) {
    return [
        'num' => $faker->randomNumber(),
        'username' => $faker->userName,
        'from' => $faker->date(),
        'to' => $faker->date(),
        'password' => bcrypt($faker->password),
        'locker_id' => $faker->randomNumber(),
    ];
});
