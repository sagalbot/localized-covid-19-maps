<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Report::class, function (Faker $faker) {
    return [
        'date' => $faker->date(),
        'confirmed' => $faker->numberBetween(0 - 100000),
        'recovered' => $faker->numberBetween(0 - 100000),
        'deaths' => $faker->numberBetween(0 - 100000),
        'country_id' => function () {
            return factory(\App\Country::class)->create()->id;
        },
        'province_id' => function () {
            return factory(\App\Province::class)->create()->id;
        },
    ];
});
