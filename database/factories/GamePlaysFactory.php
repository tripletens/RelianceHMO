<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Game_plays;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Game_plays::class, function (Faker $faker) {
    // `game_id`, `player_id`, `host_id`, `score`, `code`,
    return [
        'game_id' => rand(1,55), // this should be dynamic based on the number of games available 
        'player_id' => rand(1,10000), // 
        'host_id' => rand(1,10000),
        'score' => $faker->number,
        'code' => Str::random(10)
    ];
});
