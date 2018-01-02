<?php

use Faker\Generator as Faker;

$factory->define(Waldo\Branch::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
