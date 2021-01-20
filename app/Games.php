<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    //
    protected $fillable = [
        'name', 'version', 'date_added'
    ];
    
    public function gameplays()
    {
        return $this->hasMany('App\GamePlay','game_id','id');
    }

    public function playergames()
    {
        return $this->hasMany('App\PlayerGames','game_id','id');
    }
}
