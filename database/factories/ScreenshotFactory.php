<?php

use Faker\Generator as Faker;

$factory->define(Waldo\Screenshot::class, function (Faker $faker) {
    return [
        'id' => $faker->sha1,
        'env' => 'ci',
        'suite' => $faker->word,
        'feature' => $faker->word,
        'scenario' => $faker->sentence(3),
        'step' => $faker->sentence(6)
    ];
});
