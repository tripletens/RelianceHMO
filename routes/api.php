<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([ 'prefix' => 'games'], function (){ 
    Route::get('all', 'API\GamesController@get_all_games');
    Route::post('player_game/add', 'API\GamesController@add_player_game');
    Route::post('solo/start', 'API\GamesController@start_solo_game');
    Route::post('team/start', 'API\GamesController@start_game_team');
    Route::post('team/join', 'API\GamesController@join_team_game');
}); 

Route::group([ 'prefix' => 'players'], function (){ 
    Route::get('all', 'API\PlayerController@all_players_details');
    Route::post('fetch_player', 'API\PlayerController@fetch_one_player_details');
    Route::post('register', 'API\PlayerController@register_player');
}); 