<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    $phone = $faker->phoneNumber;

    return [
        'name' => $faker->name,
        'phone' => $phone,
        'phone_last' =>  substr($phone, -4)
    ];
});
