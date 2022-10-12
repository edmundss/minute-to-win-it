<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Comment extends Model
{

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function user_name()
	{
		$user = $this->user()->get();

		return $user->firstname;
	}

	public static function save_comment($parent_class, $parent_id, $comment)
	{
		$c = new Comment();
		$c->parent_class = $parent_class;
		$c->parent_id = $parent_id;
		$c->comment = $comment;
		$c->user_id = Auth::user()->id;
		$c->save();
	}
}
