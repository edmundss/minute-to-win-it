<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function responsible()
    {
    	return $this->belongsTo('App\User', 'responsible_id');
    }

    public function author()
	{
		return $this->belongsTo('App\User', 'author_id');
	}

    public function comments()
	{
		return $this->hasMany('App\Comment', 'parent_id')->where('parent_class', '=', 'Task')->orderBy('created_at','DESC');
	}

    public function attachments()
	{
		return $this->hasMany('App\Attachment', 'parent_id')->where('parent_class', '=', 'Task')->orderBy('created_at','DESC');
	}

    public function subtasks()
	{
		return $this->hasMany('App\Task', 'parent_id')->where('parent_class', '=', 'Task')->orderBy('created_at');
	}
}
