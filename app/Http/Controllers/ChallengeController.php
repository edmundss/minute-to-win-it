<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use DB;
use Datatables;

Use App\Challenge;

class ChallengeController extends Controller
{
   	public function index()
   	{
   		$session_owner = Auth::user();

   		$params = array(
   			'session_owner' => $session_owner,
   			'page_title' => 'Izaicinājumi',
   			'sub_title' => 'saraksts',
   		);

   		return view('challenge.index')->with($params);
   	}

   	public function create()
   	{
   		$session_owner = Auth::user();

   		$params = array(
   			'session_owner' => $session_owner,
   			'page_title' => 'Izaicinājumi',
   			'sub_title' => 'pievienošana',
   		);

   		return view('challenge.create')->with($params);
   	}

    public function store(Request $request)
    {
    	$this->validate(
            $request, 
            array(
                'title'=>'required',
                'video_link'=>'required',
            )
        );


		$url = $request->input('video_link');
		parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
		$youtube_code = $my_array_of_vars['v']; 

    	$challenge = Challenge::create([
    		'title' => $request->input('title'),
    		'video_link' => $youtube_code,
    		'user_id' => Auth::id(),
    	]);

    	return redirect()->route('challenge.show', $challenge->id)->withMessage('Izaicinājums ir pievienots. Veiksmi!');
    }

    public function show($id)
    {
    	$challenge = Challenge::findOrFail($id);

   		$params = array(
   			'session_owner' => Auth::user(),
   			'page_title' => 'Izaicinājumi',
   			'sub_title' => 'kartiņa',
   			'challenge' => $challenge
   		);

    	return view('challenge.show')->with($params);
    }



   	public function datatable(Request $request)
    {
        $challenges = DB::table('challenges')
        	->join('users', 'users.id', '=', 'challenges.user_id')
            ->select([
            	'challenges.id',
            	'challenges.created_at',
            	'challenges.title',
            	'users.name as user',
            	'users.id as user_id',
            ]);

        return Datatables::of($challenges)
            ->editColumn('title', function($item){
                return '<a href="'.route('challenge.show', $item->id).'">'.$item->title.'</a>';
            })
            ->editColumn('user', function($item){
                return '<a href="'.route('user.show', $item->user_id).'">'.$item->user.'</a>';
            })
            ->make(true);
    }
}
