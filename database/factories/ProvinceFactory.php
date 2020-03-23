<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Province::class, function (Faker $faker) {
    return [
        'name' => $faker->state,
        'country_id' => function () {
            return factory(\App\Country::class)->create()->id;
        },
    ];
});
