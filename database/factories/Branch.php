<?php

use Faker\Generator as Faker;

$factory->define(\App\Branch::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'instruction_link' => $faker->url,
    ];
});
