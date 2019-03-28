<?php

use Faker\Generator as Faker;

$factory->define(\App\Question::class, function (Faker $faker) {
    return [
        'title' => $faker->address,
        'body' => $faker->paragraph,
        'user_id' => 2
    ];
});

$factory->define(\App\Topic::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'bio' => $faker->paragraph,
    ];
});
