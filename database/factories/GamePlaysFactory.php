<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\GamePlay;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(GamePlay::class, function (Faker $faker) {
    // `game_id`, `player_id`, `host_id`, `score`, `code`,
    return [
        'game_id' => rand(1,55), // this should be dynamic based on the number of games available 
        'player_id' => rand(1,10000), // 
        'host_id' => rand(1,10000),
        'score' => rand(1,10000),
        'code' => Str::random(10)
    ];
});
