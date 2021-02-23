<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Reservation;
use Faker\Generator as Faker;

$factory->define(Reservation::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->sentence,
        'asset_id' => $faker->randomNumber(),
        'date' => $faker->date(),
        'start_time' => $faker->time(),
        'end_time' => $faker->time(),
        'approval_status' => $faker->randomElement(['already_approved'])
    ];
});
