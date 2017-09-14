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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'uuid' => $faker->uuid,
        'mobile' => $faker->phoneNumber,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Article::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(1),
        'sub_title' => $faker->sentence(6),
        'content' => $faker->paragraph(10),
        'pictures' => 'http://imgs.soufun.com/viewimage/house/2013_02/19/sh/1361263843390_000/480x320.jpg',
        'author' => 11
    ];
});

$factory->define(App\House::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(1),
        'estate_id' => $faker->numberBetween(1, 5),
        'area' => $faker->numberBetween(50, 200),
        'room_count' => $faker->numberBetween(1, 4),
        'parlour_count' => $faker->numberBetween(0, 2),
        'toilet_count' => $faker->numberBetween(1, 2),
        'kitchen_count' => 1,
        'photos' => 'http://imgs.soufun.com/viewimage/house/2013_02/19/sh/1361263843390_000/480x320.jpg',
    ];
});

$factory->define(App\RentHouse::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(1),
        'rent_price' => $faker->numberBetween(1000, 15000),
        'floor' => $faker->numberBetween(1, 40)
    ];
});

$factory->define(App\OldHouse::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(1),
        'sale_price' => $faker->numberBetween(30, 10000),
        'floor' => $faker->numberBetween(1, 40),
    ];
});

