<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerGames extends Model
{
    //
    protected $fillable = [
        'player_id', 'game_id'
    ];

    public function player()
    {
        return $this->belongsTo('App\Players','player_id','id');
    }

    public function games()
    {
        return $this->belongsTo('App\Games','game_id','id');
    }
}
