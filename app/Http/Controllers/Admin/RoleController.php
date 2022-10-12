<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
Use App\Role;
Use App\Permission;

class RoleController extends Controller
{
     public function index()
    {
    	$session_owner = Auth::user();
    	$roles = Role::all();

    	$params = array(
    		'session_owner' => $session_owner,
    		'page_title' => 'Lomas',
    		'sub_title' => 'saraksts',
    		'roles' => $roles,
    	);

    	return view('admin.roles.index')->with($params);
    }

   	public function create()
    {
    	$session_owner = Auth::user();

    	$params = array(
    		'session_owner' => $session_owner,
    		'page_title' => 'Jaunas lomas izveide',
    	);

    	return view('admin.roles.create')->with($params);    	
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

        $role = Role::create([
            'name' => $name,
            'display_name' => $display_name,
            'description' => $description,
        ]);

        return redirect()->route('admin.roles.show', $role->id)->withMessage('Jauna loma ir pievienota.');
    }

    public function show($id)
    {
    	$session_owner = Auth::user();
    	$role = Role::findOrFail($id);
        $permissions = Permission::all();

    	$params = array(
    		'session_owner' => $session_owner,
    		'page_title' => 'Lomas',
    		'sub_title' => 'kartiņa',
    		'role' => $role,
            'permissions' => $permissions,
    	);

    	return view('admin/roles/show')->with($params);  

    }

    public function update_permissions(Request $request)
    {
        $role = Role::findOrFail($request->input('role'));
        $permission = Permission::findOrFail($request->input('permission'));
        if($request->input('assigned') == 'true'){
            $role->attachPermission($permission);
            return 'Tiesības ir sekmīgi piešķirtas';
        } else {
            $role->detachPermission($permission);
            return 'Tiesības ir sekmīgi noņemtas';
        }
    }
}