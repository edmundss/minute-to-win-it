<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\User;
Use App\Round;

class RoundPolicy
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

    /**
     * Determine if the given round can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Round  $round
     * @return bool
     */

    public function set_winner(User $user, Round $round)
    {
        //der arī likmju noteikšanai

        $is_owner = $user->id === $round->game->user_id;

        $next_round = Round::where('game_id', $round->game_id)->where('round', $round->round + 1)->first();

        return $is_owner && !$next_round && $round->game->active;
    }

    public function add_video(User $user, Round $round)
    {
        return $user->id === $round->game->user_id;
    }
}
