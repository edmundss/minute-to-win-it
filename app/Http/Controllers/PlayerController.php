<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Gate;

use App\Player;
use App\Picture;
use App\Game;

class PlayerController extends Controller
{
    public function store(Request $request)
    {
    	$this->validate(
            $request, 
            array(
                'name'=>'required',
                'game_id'=>'required',
                'picture'=>'required',
            )
        );

    	$game_id = $request->input('game_id');
        $game = Game::findOrFail($game_id);


        if (Gate::denies('update', $game)) {
               return redirect()->back()->withErrors(['Jaunus spēlētājus pievienot var tikai spēles īpašnieks!']);
        }

    	$player = Player::create([
    		'game_id' => $game_id,
    		'name' => $request->input('name')
    	]);

    	$picture = $request->file('picture');

    	Picture::store($player, $picture);

    	return redirect()->route('game.show', $game_id)->withMessage($player->name . ' ir pievienots(-a) spēlei. Vaiksmi!');
    }
}
