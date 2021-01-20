<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GamePlay extends Model
{
    //
    protected $fillable = [
        'game_id', 'host_id', 'player_id','code'
    ];

    public function games()
    {
        return $this->belongsTo('App\Games','game_id','id');
    }

    public function playergames()
    {
        return $this->belongsTo('App\PlayerGames','game_id','id');
    }
    
    public function players()
    {
        return $this->belongsTo('App\Players','player_id','id');
    }
    
}
