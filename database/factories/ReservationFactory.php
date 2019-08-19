<?php

use Faker\Generator as Faker;

$factory->define(App\Reservation::class, function (Faker $faker) {
    $from = \Carbon\Carbon::parse($faker->date($format = 'Y-m-d', $max = 'now'));
    $to = $from->addHour(1);

    $branch = factory(\App\Branch::class)->create();

    return [
        'name' => $faker->name,
        'phone' => $faker->phoneNumber,
        'room' => array_rand($branch->getRoomTypes()),
        'from' => $from,
        'to' => $to,
        'branch_id' => $branch->id,
    ];
});
