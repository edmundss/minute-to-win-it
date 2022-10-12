<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use Gate;
use DB;
use Datatables;

use App\Game;
use App\Challenge;

class GameController extends Controller
{
   	public function index()
   	{
   		$session_owner = Auth::user();

   		$params = array(
   			'session_owner' => $session_owner,
   			'page_title' => 'Spēles',
   			'sub_title' => 'saraksts',
   		);

   		return view('game.index')->with($params);
   	}

   	public function create()
   	{

   		$game = Game::create([
   			'user_id' => Auth::id(),
   			'active' => true,
   		]);

   		return redirect()->route('game.show', $game->id)->withMessage('Spēle ir izveidota. Veiksmi!');
   	}

   	public function show ($id)
   	{
   		$session_owner = Auth::user();
        $game = Game::findOrFail($id);

   		$params = array(
   			'session_owner' => $session_owner,
   			'page_title' => 'Spēle',
   			'sub_title' => '#' . $game->id,
   			'game' => $game,
            'done_challenges' => $game->rounds()->pluck('challenge_id')->toArray(),
            'selected_challenges' => $game->challenges()->pluck('challenges.id')->toArray(),

   		);

   		return view('game.show')->with($params);

   	}

    public function toggle_challenge($id, Request $request)
    {
        $game = Game::findOrFail($id);
        $challenge = Challenge::find($request->challenge_id);


        if($request->add == 'true') {
            $game->challenges()->attach($request->challenge_id);
            return [
                'add' => true,
                'challenge' => $challenge
            ];
        }else{
            $game->challenges()->detach($request->challenge_id);
            return [
                'add' => false,
                'challenge' => $challenge
            ];

        }
    }


   	public function datatable(Request $request)
    {
        $games = DB::table('games')
        	->join('users', 'users.id', '=', 'games.user_id')
            ->select([
            	'games.id',
            	'games.created_at',
            	'users.name as user',
            	'users.id as user_id',
            	'games.active',
            ]);

        return Datatables::of($games)
            ->editColumn('created_at', function($item){
                return '<a href="'.route('game.show', $item->id).'">'.$item->created_at.'</a>';
            })
            ->editColumn('active', function($item){
                return ($item->active)?'Aktīva':'Pabeigta';
            })
            ->make(true);
    }

    public function delete($id)
    {
      $game = Game::findOrFail($id);

        if (Gate::denies('delete', $game)) {
               return redirect()->back()->withErrors(['Spēli var dzēst tikai tās īpašnieks!']);
        }

      $game->delete();

      return redirect()->route('game.index')->withMessage('Spēle ir dzēsta!');
    }

    public function deactivate($id)
    {
      $game = Game::findOrFail($id);

        if (Gate::denies('update', $game)) {
          return redirect()->back()->withErrors(['Spēli deaktivizēt var tikai tās īpašnieks!']);
        }

      $game->update([
        'active' => false,
      ]);

      return redirect()->back()->withMessage('Spēle ir dzēsta!');
    }
}
