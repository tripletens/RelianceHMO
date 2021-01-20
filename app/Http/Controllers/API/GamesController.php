<?php

namespace App\Http\Controllers\API;

use App\GamePlay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Games;
use App\Players;
use App\PlayerGames;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use DB;


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
        // checks if game exists 
        $this->if_game_exists($game_id);

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

        $game_details = Games::find($game_id);

        $player_name = $player_exists->name;
        $game_name = $game_details->name;

        $game_version = $game_details->version;

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
        $player_id = $request->player_id;

        $code = Str::random(10);
        // since its a solo game player_id will be the host_id too

        $validator = Validator::make($request->all(), [
            'player_id' => 'required',
            'game_id' => 'required',
            'player_id' => 'required'
        ]);

        if ($validator->fails()) {
            $data =  [
                'status' => 'error',
                'message'  => 'Validation Error',
                'data' => $validator->errors()
            ];
    
            return response()->json($data,404);
        }

        // CHECK if player has already logged in today 
        $today = date("Y-m-d");  

        $gameplay = GamePlay::where('host_id',$host_id)
                        ->where('game_id',$game_id)->whereDate('created_at', $today);

        

        if($gameplay->count() > 0){
            $data =  [
                'status' => 'success',
                 'message'  => 'Game Play Record Already Exists',
                 'data' => $gameplay
             ];
             
            return response()->json($data,200);      
        }

        $save_game_play  = GamePlay::create([
            'host_id' => $host_id,
            'game_id' => $game_id,
            'player_id' => $player_id,
            'code' => $code
        ]);

        if($save_game_play){
            $data =  [
                'status' => 'success',
                'message'  => 'Game play with code ' . $code . ' added',
                'data' => $save_game_play
            ];
    
            return response()->json($data,200);
        }

    }

    public function start_game_team(Request $request){
        // this starts a team game 
        
        $host_id = $request->host_id;
        $game_id = $request->game_id;
        $player_id = $request->player_id;

        $code = Str::random(10);
        
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|integer',
            'game_id' => 'required|integer',
            'player_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $data =  [
                'status' => 'error',
                'message'  => 'Validation Error',
                'data' => $validator->errors()
            ];
    
            return response()->json($data,404);
        }

        $save_game_play  = GamePlay::create([
            'host_id' => $host_id,
            'game_id' => $game_id,
            'player_id' => $player_id,
            'code' => $code
        ]);

        if($save_game_play){
            
            $data =  [
                'status' => 'success',
                'message'  => 'Game play with code ' . $code . ' added',
                'data' => $save_game_play
            ];
    
            return response()->json($data,200);
        }
    }

    public function join_team_game(Request $request){
        // having a maximum of 4 players and 
        // only players with same version of game can play 

        $host_id = $request->host_id;
        $game_id = $request->game_id;
        $player_id = $request->player_id;

        $code = $request->code;
        
        $validator = Validator::make($request->all(), [
            'host_id' => 'required',
            'game_id' => 'required',
            'player_id' => 'required',
            'code' => 'required|string'
        ]);

        if ($validator->fails()) {
            $data =  [
                'status' => 'error',
                'message'  => 'Validation Error',
                'data' => $validator->errors()
            ];
    
            return response()->json($data,404);
        }

        // check team number
        
        $gameplay_no = GamePlay::where('code',$code);

        if($gameplay_no->count() > 4){
            $data =  [
                'status' => 'error',
                'message'  => 'Sorry only a Maximum of 4 members are allowed in this game',
                'data' => $gameplay_no
            ];
    
            return response()->json($data,200);
        }

        // check game version

        $player_game = PlayerGames::where('player_id',$player_id) 
                        ->where('game_id',$game_id);

        // check if the player has the game 

        if($player_game->count() == 0 ){
            $data =  [
                'status' => 'error',
                'message'  => 'Sorry Player doesnt have the game version in his/her collection',
                'data' => $player_game
            ];
    
            return response()->json($data,200);
        } 

        // check if the game has been added before the game play 

        $today = date("Y-m-d");  
        // checks that there is no game play before the date the game was added
        $game = Games::where('game_id',$game_id)->whereDate('date_added', '<' , $today);

        if($game->count() < 1 ){
            $data =  [
                'status' => 'error',
                'message'  => 'Game not registered as at today',
                'data' => []
            ];
    
            return response()->json($data,404);
        }


        // checks if the player has already joined 

        $check_player_exists = GamePlay::where('player_id',$player_id)
                                ->where('code',$code);
        

        if($check_player_exists->count() > 0){
            $game_details = Gameplay::where('code',$code)->get();
            $data =  [
                'status' => 'error',
                'message'  => 'Player already joined the game',
                'data' => $game_details
            ];
            return response()->json($data,200);
        }
        
        $save_game_play  = GamePlay::create([
            'host_id' => $host_id,
            'game_id' => $game_id,
            'player_id' => $player_id,
            'code' => $code
        ]);
        
        if($save_game_play){
            $game_details = Gameplay::where('code',$code)->get();
            $data =  [
                'status' => 'success',
                'message'  => 'Have FUN! joining the game.',
                'data' => $game_details
            ];
            return response()->json($data,200);
        }    
    }

    
    public function if_game_exists($game_id){
        $game_exists = Games::find($game_id);

        if($game_exists == null){
            $data =  [
                'status' => 'error',
                'message'  => 'Game with ID not found ',
                'data' => []
            ];
    
            return response()->json($data,404);
        }
    }
}

