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
}); 