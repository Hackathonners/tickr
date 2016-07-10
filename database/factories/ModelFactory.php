<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Karina\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Karina\RegistrationType::class, function (Faker\Generator $faker) {
    return [
        'type' => $faker->numerify('Type ##'),
        'price' => $faker->randomFloat(2, 0.95, 1.95),
        'fine' => $faker->randomFloat(2, 0, 1.25)
    ];
});

$factory->define(App\Karina\Event::class, function (Faker\Generator $faker) {
    return [
        'title' => 'Event Title',
        'description' => $faker->sentence,
        'place' => $faker->city,
        'start_at' => $faker->dateTime->format('Y-m-d H:i:s'),
        'end_at' => $faker->dateTime->format('Y-m-d H:i:s'),
    ];
});
