<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['user_id', 'active'];

    public function rounds()
    {
    	return $this->hasMany('App\Round');
    }

    public function players()
    {
    	return $this->hasMany('App\Player');
    }

    public function challenges()
    {
        return $this->belongsToMany('App\Challenge');
    }
}
