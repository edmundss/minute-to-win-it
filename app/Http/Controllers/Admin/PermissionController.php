<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use App\Permission;

class PermissionController extends Controller
{
    public function index()
    {
    	$session_owner = Auth::user();
    	$permissions = Permission::all();

    	$params = array(
    		'session_owner' => $session_owner,
    		'page_title' => 'Tiesības',
    		'sub_title' => 'saraksts',
    		'permissions' => $permissions,
    	);

    	return view('admin.permissions.index')->with($params);
    }

   	public function create()
    {
    	$session_owner = Auth::user();

    	$params = array(
    		'session_owner' => $session_owner,
    		'page_title' => 'Jaunu  tiesību izveide',
    	);

    	return view('admin.permissions.create')->with($params);    	
    }

    public function store(Request $request)
    {
    	$this->validate(
            $request, 
            array(
                'name'=>'required',
                'display_name'=>'required',
            )
        );

        $name = $request->input('name');
        $display_name = $request->input('display_name');
        $description = $request->input('description');

        $permission = Permission::create([
        	'name' => $name,
        	'display_name' => $display_name,
        	'description' => $description,
        ]);

        return redirect()->route('admin.permissions.index')->withMessage('Jaunas tiesības ir izveidotas.');
    }
}
