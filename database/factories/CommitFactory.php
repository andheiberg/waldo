<?php

use Faker\Generator as Faker;

$factory->define(Waldo\Commit::class, function (Faker $faker) {
    return [
        'hash' => $faker->sha1,
    ];
});
