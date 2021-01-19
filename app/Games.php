<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    //
    protected $fillable = [
        'name', 'version', 'date_added'
    ];
}
