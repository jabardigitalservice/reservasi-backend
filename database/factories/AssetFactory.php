<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Psy\Util\Str;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'status' => $faker->randomElements(['active','not_active']),
        'description' => Str::random(50)
    ];
});
