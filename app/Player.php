<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['name', 'game_id'];

    public function picture_dimensions()
	{
		return array(
			['action' => 'widen',
			'title' => 'full',
			'w' => '800'],
			['action' => 'fit',
			'title' => 'big',
			'w' => '600',
			'h' => '600'],
			['action' => 'fit',
			'title' => 'thumb',
			'w' => '80',
			'h' => '80'],
			['action' => 'fit',
			'title' => 'xs',
			'w' => '30',
			'h' => '30'],
		);
	}

	public function getAvatar($size)
    {
        if($this->avatar)
        {
            return asset('image/Player/' . $this->avatar->id . '/'. $size . '.jpg');
        } else {
            return asset('dist/img/user2-160x160.jpg');
        }

    }

    public function avatar()
    {
        return $this->hasOne('App\Picture', 'parent_id')->where('parent_class', 'Player')->orderBy('created_at', 'DESC');
    }
}
