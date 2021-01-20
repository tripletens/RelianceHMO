<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Players;
use App\GamePlay;
use App\PlayerGames;
use App\Games;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Hash;

class PlayerController extends Controller
{
    //
    public function all_players_details(){

        $players = Players::paginate(10);

        
        $data =  [
            'status' => 'success',
            'message'  => 'List of all the players',
            'data' => $players
        ];

        return response()->json($data,200);

    }   

    public function fetch_one_player_details (Request $request) {

        $player_id = $request->player_id;

        $validator = Validator::make($request->all(), [
            'player_id' => 'required',
        ]);

        if ($validator->fails()) {
            $data =  [
                'status' => 'error',
                'message'  => 'Validation Error',
                'data' => $validator->errors()
            ];
    
            return response()->json($data,404);
        }

        $player = Players::find($player_id);

        if($player == null){
            $data =  [
                'status' => 'error',
                'message'  => 'Player Record not found',
                'data' => $player
            ];
    
            return response()->json($data,404);
        }

        $playergames = $player->playergames;

        $game_plays = $player->gameplays;

        $all_player_games = [];

        foreach($playergames as $key => $playergame){
            
            $player_owned_games = Games::find($playergames[$key]->game_id);
            array_push($all_player_games,$player_owned_games);
        }

        $data =  [
            'status' => 'success',
            'message'  => 'List of all the players',
            'data' => [
                'player' => $player,
                'player\'s games' =>  $all_player_games,
                'game_plays' => $game_plays
            ]
        ];

        return response()->json($data,200);
    }
    public function register_player(Request $request){
        // name, email, nickname, password, date joined, last login
        $name = $request->name;
        $email = $request->email;
        $nickname = $request->nickname;
        $password = $request->password;

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:players'],
            'password' => ['required', 'string', 'min:8'],
            'nickname' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            $data =  [
                'status' => 'error',
                'message'  => 'Validation Error',
                'data' => $validator->errors()
            ];
    
            return response()->json($data,404);
        }


        $save_record =  Players::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'nickname' => $nickname,
            'date_joined' => date('Y-m-d'),
            'last_login' => date('Y-m-d')
        ]);

        if(!$save_record){
            $data =  [
                'status' => 'error',
                'message'  => 'Sorry Currently Unable to register player',
                'data' => null
            ];
    
            return response()->json($data,200);
        }

        $data =  [
            'status' => 'success',
            'message'  => 'Player successfully registered',
            'data' => $save_record
        ];

        return response()->json($data,200);
    }   
}
