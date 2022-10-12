<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    protected $fillable = ['player_id', 'round_id', 'challenger_id', 'bet'];

    public function player()
    {
    	return $this->belongsTo('App\Player', 'player_id');

    }

    public function round()
    {
    	return $this->belongsTo('App\Round', 'round_id');

    }
}
