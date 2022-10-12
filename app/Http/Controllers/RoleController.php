<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;

use App\Role;

class RoleController extends Controller
{
    public function index()
    {
    	$session_owner = Auth::user();
    	$roles = Role::all();

    	$params = array(
    		'session_owner' => $session_owner,
    		'page_title' => 'Lomas / amati',
    		'sub_title' => 'saraksts',
    		'roles' => $roles,
    	);

    	return view('roles.index')->with($params);
    }

    public function store(Request $request)
    {
    	$this->validate(
            $request, 
            array(
                'title'=>'required',
            )
        );

    	$role = new Role;
    	$role->title = $request->input('title');
    	$role->save();

    	//MeasurementUnit::create(['title' => $request->input('title')]);

    	return redirect()->back()->withMessage('Jauna loma ir pievienota.');
    }
}
