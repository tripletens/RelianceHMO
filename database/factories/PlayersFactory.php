<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Players;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Players::class, function (Faker $faker) {
    return [
        // 'name', 'email','nickname','date_joined','last_login', 'password',
        //
        'name' => $faker->name,
        'nickname' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'date_joined' => $faker->date,
        'last_login' => $faker->date,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => Str::random(10),
    ];
});
