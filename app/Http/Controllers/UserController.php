<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Intervention\Image\ImageManagerStatic as Image;


use DB;
use Auth;
use File;

use App\User;
use App\Picture;
use App\Role;

class UserController extends Controller
{    

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$users = User::orderBy('name')->get();

		$first_char = '';
		$user_array = [];

		foreach ($users as $u) {
			if($first_char != $u->name[0])
			{
				$first_char = $u->name[0];
			}
			$user_array[$first_char][] = $u;
		}

		$params = array(
			'page_title' => 'Lietotāju saraksts',
			'user_array' => $user_array,
			'session_owner' => Auth::user(),
		);

		return view('users.index')->with($params);
	}

	public function show($id)
	{
		$session_owner = Auth::user();
		$user = User::findOrFail($id);
		

		//$roles = Role::join('role_user', function($join)->get();

		$roles = Role::leftJoin('role_user', function($join) use ($id){
			$join->on('roles.id', '=', 'role_user.role_id')
				->where('role_user.user_id', '=', $id);
		})
			->select(['roles.display_name', 'roles.id', 'role_user.user_id as assigned'])
			->get();

		$params = [
			'page_title' => $user->name,
			'sub_title' => 'profils',
			'employee' => $user,
			'session_owner' => $session_owner,
			'roles' => $roles,
			'role_manager' => true,
			'tasks' => $user->tasks,
		];

		return view('users.show')->with($params);
	}

	public function json(Request $request)
	{
		$q = $request->input('q');
		$users = DB::table('users')
			->where('name', 'like', '%' . $q . '%')
			->select('id', DB::raw("name as text"))
			->get();

		return $users;
	}

	public function upload_avatar(Request $request)
	{
		$session_owner = Auth::user();
		$picture_model = Picture::create(['parent_class' => 'User' ,'parent_id' => $session_owner->id]);

		try {
		 	$picture = $request->file('avatar');
		
			$img = Image::make($picture);
		
			$path = 'image/avatars/' . $picture_model->id . '/';
			$result = File::makeDirectory($path, 0775, true, true);
			
			$img->save($path . 'original.jpg', 80);	
			
			$full = $img->widen(450);
			$full->save($path . 'full.jpg', 80);

			$thumb = $img->fit(250, 250);
			$thumb->save($path . 'big.jpg', 80);

			$thumb = $img->fit(80, 80);
			$thumb->save($path . 'thumb.jpg', 80);

			$thumb = $img->fit(30, 30);
			$thumb->save($path . 'xs.jpg', 80);

			return redirect()->back()->withMessage('Profila bilde ir nomainīta.');
		} catch (\Exception $e) {
			$picture_model->delete();
			return redirect()->back()->withErrors(['Bildes ielāde neizdevās. Iespējams, bojāts, pārāk liels vai neatbilstīts faila formāts.']);
		}
	}

		// update add/remove user's role
	public function updateRoles(Request $request) {

		$assign = $request->input('assign');
		$user_id = $request->input('user_id');
		$user = User::findOrFail($user_id);
		$role_id = $request->input('role_id');

		//add or remove roles
		if ($assign == 'true') {
			$user->roles()->attach($role_id);
			return 'Loma ir piešķirta!';
		} else {
			$user->roles()->detach($role_id);
			return 'Loma ir noņemta!';
		}
	}

	public function update_phone($id, Request $request) {
		User::findOrFail($id)->update(['phone' => $request->input('phone')]);
	}
}
