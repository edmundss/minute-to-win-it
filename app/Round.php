<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $fillable = ['game_id', 'round', 'challenge_id', 'player1_id', 'player2_id', 'winner_id', 'video_link'];

    public function challenge()
    {
    	return $this->belongsTo('App\Challenge');
    }

    public function player1()
    {
    	return $this->belongsTo('App\Player', 'player1_id');
    }

    public function player2()
    {
    	return $this->belongsTo('App\Player', 'player2_id');
    }

    public function winner()
    {
    	return $this->belongsTo('App\Player', 'winner_id');
    }

    public function game()
    {
    	return $this->belongsTo('App\Game', 'game_id');
    }

    public function bets()
    {
    	return $this->hasMany('App\Bet');
    }
}
