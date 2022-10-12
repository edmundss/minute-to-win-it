<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Gate;

use App\Bet;
use App\Round;

class BetController extends Controller
{
    public function store(Request $request)
    {

    	$this->validate(
            $request,
            array(
                'challenger_id'=>'required',
                'player_id'=>'required',
                'bet'=>'required',
                'round_id'=>'required',
            )
        );

        $round_id = $request->input('round_id');
        $round = Round::findOrFail($round_id);

/*
        if (Gate::denies('set_winner', $round)) {
               return redirect()->back()>withErrors(['Likmes vairs netiek pieņemtas!']);
        }

        */
        $bet = Bet::create([
        	'round_id' => $round_id,
        	'player_id' => $request->input('player_id'),
        	'challenger_id' => $request->input('challenger_id'),
        	'bet' => $request->input('bet'),
        ]);

        return redirect()->back()->withMessage('Likme ir pieņemta. Veiksmi!');
    }

    public function delete($id)
    {
    	$bet = Bet::findOrFail($id);

        if (Gate::denies('set_winner', $bet->round)) {
               return redirect()->back()->withErrors(['Likmes vairs netiek pieņemtas!']);
        }

    	$bet->delete();

    	return redirect()->back()->withMessage('Likme ir atcelta!');
    }
}
