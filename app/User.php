<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait; // add this trait to your user model

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'google_id', 'rent', 'service'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatar($size)
    {
        if($this->avatar)
        {
            return asset('image/avatars/' . $this->avatar->id . '/'. $size . '.jpg');
        } else {
            return asset('dist/img/user2-160x160.jpg');
        }

    }

    public function tasks()
    {
        return $this->hasMany('App\Task', 'responsible_id');
    }

    public function avatar()
    {
        return $this->hasOne('App\Picture', 'parent_id')->where('parent_class', 'User')->orderBy('created_at', 'DESC');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }
}
