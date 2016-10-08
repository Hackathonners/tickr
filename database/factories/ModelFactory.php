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
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Karina\RegistrationType::class, function (Faker\Generator $faker) {
    return [
        'type' => $faker->numerify('Type ##'),
        'price' => $faker->randomFloat(2, 0.95, 1.95),
        'fine' => $faker->randomFloat(2, 0, 1.25),
    ];
});

$factory->define(App\Karina\Event::class, function (Faker\Generator $faker) {
    return [
        'title' => 'Event Title',
        'description' => $faker->sentence,
        'place' => $faker->city,
        'start_at' => (new DateTime())->modify('+1 day')->format('Y-m-d H:i:s'),
        'end_at' => (new DateTime())->modify('+5 day')->format('Y-m-d H:i:s'),
    ];
});

$factory->define(App\Karina\GuestList::class, function (Faker\Generator $faker) {
    return [
        'name' => 'Guest Title',
        'description' => $faker->sentence,
    ];
});

$factory->define(App\Karina\Guest::class, function (Faker\Generator $faker) {
    return [
        'name' => 'Guest Name',
        'notes' => $faker->sentence,
    ];
});

$factory->define(App\Karina\Registration::class, function (Faker\Generator $faker) {
    return [
        'fined' => false,
        'activated' => false,
        'activation_code' => str_random(10),
    ];
});
