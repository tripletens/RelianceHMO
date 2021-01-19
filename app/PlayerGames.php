<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerGames extends Model
{
    //
    protected $fillable = [
        'player_id', 'game_id'
    ];

}
