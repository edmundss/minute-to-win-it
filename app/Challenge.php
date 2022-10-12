<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $fillable = ['title', 'video_link', 'user_id'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function rounds()
    {
    	return $this->hasMany('App\Round');
    }
}
