<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
Use App\Game;

class GamePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Game $game)
    {
        return true;

        //return $user->id === $game->user_id && $game->active;
    }

    public function delete(User $user, Game $game)
    {
        return $user->id === $game->user_id;
    }
}
