<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Games;
use App\Players;
use App\PlayerGames;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class GamesController extends Controller
{
    //
    public function get_all_games(){
        $games = Games::paginate(10);
        
        $data =  [
            'status' => 'success',
            'message'  => 'List of all the games',
            'data' => $games
        ];

        return response()->json($data,200);
    }
    public function add_player_game(Request $request){
        // this adds the games a player has 
        $player_id = $request->player_id;
        $game_id = $request->game_id;

        $validator = Validator::make($request->all(), [
            'player_id' => 'required',
            'game_id' => 'required',
        ]);

        if ($validator->fails()) {
            $data =  [
                'status' => 'error',
                'message'  => 'Validation Error',
                'data' => $validator->errors()
            ];
    
            return response()->json($data,404);
        }

        $player_exists = Players::find($player_id);

        if($player_exists == null){

            $data =  [
                'status' => 'error',
                'message'  => 'Player with ID not found ',
                'data' => []
            ];
    
            return response()->json($data,404);
        }

        $game_exists = Games::find($game_id);

        if($game_exists == null){
            $data =  [
                'status' => 'error',
                'message'  => 'Game with ID not found ',
                'data' => []
            ];
    
            return response()->json($data,404);
        }
        
        $check_user_collection = PlayerGames::where('player_id',$player_id)
                                    ->where('game_id',$game_id);
                                    
        if($check_user_collection->count() > 0 ){
            $data =  [
                'status' => 'error',
                'message'  => 'Player already has the game in his/her game collections',
                'data' => []
            ];
    
            return response()->json($data,404);
        }

        // save the details to the db 
       
        $add_player_games = PlayerGames::create([
            'player_id' => $player_id,
            'game_id' => $game_id
        ]);

        $player_name = $player_exists->name;
        $game_name = $game_exists->name;

        $game_version = $game_exists->version;

        if($add_player_games){
            $data =  [
                'status' => 'success',
                'message'  => $player_name . ' added ' . $game_name . ' ' . $game_version . ' version to player\'s game collection',
                'data' => $add_player_games
            ];
    
            return response()->json($data,200);
        }

    }
    public function start_solo_game(Request $request){
        // this starts a solo game by one player 
        // the person selects from his/her game 
        // `game_id`, `player_id`, `host_id`, `score`, `code`,
        $host_id = $request->host_id;
        $game_id = $request->game_id;

        $validator = Validator::make($request->all(), [
            'player_id' => 'required',
            'game_id' => 'required',
        ]);

        if ($validator->fails()) {
            $data =  [
                'status' => 'error',
                'message'  => 'Validation Error',
                'data' => $validator->errors()
            ];
    
            return response()->json($data,404);
        }
    }

    public function start_game_team(){
        // this starts a team game 
        // having a maximum of 4 players and 
        // only players with same version of game can play 
    }

    public function check_team_number(){
        // check the number of members in a team 
    }

    public function check_game_versions($player_one,$player_two){
        // check the game versions team members have 
    }

    public function game_date_check($game_id,$date){
        // checks that there is no game play before the date the game was added
        // $game = Games::find($game_id);

        // if($game->game_added < ){

        // }
    }
}
