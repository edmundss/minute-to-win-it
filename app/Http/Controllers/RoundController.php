<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use DB;
use Gate;

use App\Round;
use App\Challenge;
use App\Player;
use App\Bet;
use App\Game;

class RoundController extends Controller
{
   	public static function get_random_player_id($request, $game_id)
      {
         //nosaka spēlētājus
         //paņem nespēlējušos no sesijas
         $player_ids = $request->session()->get($game_id . '_player_ids');

         //ja nespēlējušo saraksta sesijā nav vai tas ir tukš
         if(count($player_ids) < 1)
         {
            //izveido jaunu un saglabā sesijā
            $player_ids = Player::where('game_id', $game_id)->orderByRaw('RAND()')->lists('id');
         }

         // izņem vienu no saraksta
         $random_player = $player_ids->pop();

         // salgabā samazināto sarakstu
         $request->session()->put($game_id . '_player_ids', $player_ids);

         return $random_player;
      }

      public function create(Request $request)
   	{

         $game_id = $request->input('game_id');
         $game = Game::findOrFail($game_id);

         //nosaka raundu
         $last_round = DB::table('rounds')->where('game_id', $game_id)->orderBy('round', 'DESC')->first();

         if($last_round)
         {
            if(!$last_round->winner_id)
            {
               return redirect()->back()->withMessage('Nevar sākt jaunu raundu, kamēr nav noteikts uzvarētājs!');
            }
         }

         $next_round = ($last_round)?$last_round->round+1:1;

         //nosaka spēlētājus
         $player1_id = RoundController::get_random_player_id($request, $game_id);
         $player2_id = RoundController::get_random_player_id($request, $game_id);
         //iespējams, ka vecā saraksta pēdējais un jaunā saraksta pirmais ir viens un tas pats spēlētājs
         if($player1_id == $player2_id)
         {
            //vienkārši atkārto procedūru
            $player2_id = RoundController::get_random_player_id($request, $game_id);
         }


   		//izvēlas challengu
   		$previous_challenges = Round::where('game_id', $game_id)->lists('challenge_id');
        $challenge = $game->challenges()
           ->whereNotIn('challenges.id', $previous_challenges)
           ->orderByRaw('RAND()')
           ->take(1)
           ->select('challenges.id')
           ->value('id');

   		if(!$challenge){
   			return redirect()->back()->withMessage('Visi izaicinājumi ir izspēlēti!');
   		}

         $game = Game::findOrFail($game_id);
         if (Gate::denies('update', $game)) {
               return redirect()->back()->withErrors(['Sasniegts pēdējais raunds. Jaunu izveidot nevar, jo spēle beigusies vai neesi spēles īpašnieks.']);
         }

   		$round = Round::create([
   			'game_id' => $game_id,
   			'round' => $next_round,
   			'player1_id' => $player1_id,
   			'player2_id' => $player2_id,
   			'challenge_id' => $challenge,
   		]);

   		return redirect()->route('round.show', $round->id)->withMessage('Round '. $round->id .'. FIGHT!');
   	}


      public function next($id, Request $request)
      {

         $current = Round::findOrFail($id);
         $next_round = $current->round+1;
         $game_id = $current->game_id;
         $next = Round::where('game_id', $game_id)->where('round', $next_round)->first();

         //ja nākamais raunds eksistē
         if($next) {
            return redirect()->route('round.show', $next->id);
         }

         $game = Game::findOrFail($game_id);
         if (Gate::denies('update', $game)) {
               return redirect()->back()->withErrors(['Sasniegts pēdējais raunds. Jaunu izveidot nevar, jo spēle beigusies vai neesi spēles īpašnieks.']);
        }

         //iepriekšējam vēl nav noteikti uzvarētāji
         if(!$current->winner_id)
         {
            return redirect()->back()->withMessage('Nevar sākt jaunu raundu, kamēr nav noteikts uzvarētājs!');
         }

         //izvēlas challengu
         $previous_challenges = Round::where('game_id', $game_id)->lists('challenge_id');
         $challenge = $game->challenges()
            ->whereNotIn('challenges.id', $previous_challenges)
            ->orderByRaw('RAND()')
            ->take(1)
            ->select('challenges.id')
            ->value('id');

         // visi challenge izspēlēti
         if(!$challenge){
            return redirect()->back()->withMessage('Visi izaicinājumi ir izspēlēti!');
         }

         //nosaka spēlētājus
         $player1_id = RoundController::get_random_player_id($request, $game_id);
         $player2_id = RoundController::get_random_player_id($request, $game_id);

         //iespējams, ka vecā saraksta pēdējais un jaunā saraksta pirmais ir viens un tas pats spēlētājs
         if($player1_id == $player2_id)
         {
            //vienkārši atkārto procedūru
            $player2_id = RoundController::get_random_player_id($request, $game_id);
         }

         $round = Round::create([
            'game_id' => $game_id,
            'round' => $next_round,
            'player1_id' => $player1_id,
            'player2_id' => $player2_id,
            'challenge_id' => $challenge,
         ]);

         return redirect()->route('round.show', $round->id)->withMessage('Round '. $round->id .'. FIGHT!');
      }

   	public function show($id)
   	{
   		$round = Round::findOrFail($id);
   		$challenge = $round->challenge;

   		$players = $round->game->players()->pluck('name', 'id');

   		$session_owner = Auth::user();

   		$player1_total_bets = Bet::where('round_id', $id)->where('challenger_id', $round->player1_id)->sum('bet');
   		$player1_bets = Bet::where('round_id', $id)->where('challenger_id', $round->player1_id)->get();
   		$player2_total_bets = Bet::where('round_id', $id)->where('challenger_id', $round->player2_id)->sum('bet');
   		$player2_bets = Bet::where('round_id', $id)->where('challenger_id', $round->player2_id)->get();


   		$params = array(
   			'session_owner' => $session_owner,
   			'page_title' => $round->round.'. raunds: ' . $challenge->title,
   			'round' => $round,
   			'challenge' => $challenge,
   			'player1' => $round->player1,
   			'player2' => $round->player2,
   			'players' => $players,
   			'player1_bets' => $player1_bets,
   			'player2_bets' => $player2_bets,
   			'player1_total_bets' => $player1_total_bets,
   			'player2_total_bets' => $player2_total_bets,
            'breadcrumb' => array(
               ['url'=>route('game.show', $round->game_id), 'title'=>'Spēle #' . $round->game_id],
               ['url'=>route('round.show', $round->id), 'title'=>'Raunds #' . $round->round],
            )
   		);

   		return view('round.show')->with($params);
   	}

      public function set_winner($round_id, $winner_id)
      {
         $round = Round::findOrFail($round_id);

/*
         if (Gate::denies('set_winner', $round)) {
               return redirect()->back()->withErrors(['tikai spēles īpašnieks var noteikts uzvarētāju, tikai aktīvai spēlei un tikai, ja nav sācies nākamais raunds!']);
         }
         */


         $round->update(['winner_id' => $winner_id]);

         return redirect()->back()->withMessage('Uzvarētājs ir noteikts! APSVEICU!');
      }

      public function submit_video($round_id, Request $request)
      {


         $this->validate(
            $request,
            array(
                'video_link'=>'required',
            )
         );


         $round = Round::findOrFail($round_id);

         if (Gate::denies('add_video', $round)) {
               return redirect()->back()->withErrors(['tikai spēles īpašnieks var pievienot video!']);
         }

         $url = $request->input('video_link');
         parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
         $youtube_code = $my_array_of_vars['v'];


         $round->update(['video_link' => $youtube_code]);

         return redirect()->back()->withMessage('Šis raunds ieies vēsturē!');
      }
}
