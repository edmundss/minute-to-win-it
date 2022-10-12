<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use File;
use DB;
use Artisan;

class MigrationController extends Controller
{
    	public function index() {
		//construct path to migrations folder
	    $path = base_path() . '/database/migrations';

	    //get root migrations
	    $root_migrations = File::files($path);
	    $rm = array();

	    foreach ($root_migrations as $m) {
	    	$title = explode('/', $m);
	    	$title = $title[count($title)-1];
	    	$title = explode('.', $title)[0];

	    	$migration = DB::table('migrations')->where('migration', $title)->first();

	    	if($migration)
	    	{
	    		$rm[] = array('title'=>$migration->migration, 'installed' => true, 'batch' => $migration->batch);
	    	} else {
	    		$rm[] = array('title' => $title, 'installed' => false, 'batch' => '--');
	    	}
	    }

	    // NAMESPACE MIGRATIONS
	    // folders
	    $folders = File::directories($path);
	    $nm = array();

	    foreach ($folders as $f) {
	    	$namespace_migrations = File::files($f);
	    	$array = array();

	    	foreach ($namespace_migrations as $m) {
		    	$title = explode('/', $m);
		    	$title = $title[count($title)-1];
		    	$title = explode('.', $title)[0];

		    	$migration = DB::table('migrations')->where('migration', $title)->first();

		    	if($migration)
		    	{
		    		$array[] = array('title'=>$migration->migration, 'installed' => true, 'batch' => $migration->batch);
		    	} else {
		    		$array[] = array('title' => $title, 'installed' => false, 'batch' => '--');
		    	}
		    }
		    $f = explode('/', $f);
	    	$f = $f[count($f)-1];
		    $f = explode('\\', $f);
	    	$f = $f[count($f)-1];

		    $nm[$f] = $array;
	    }

	    $params = array(
	    	'root_migrations' => $rm,
	    	'namespace_migrations' => $nm,
	    );

	    return view('admin.migrations.index')->with($params);

	}

	public function install(Request $request)
	{
		$namespace = $request->input('namespace');


		try {
	        echo 'Let`s start';
	        echo '<br>init migrate:install...';
	        if ($namespace)
	        {
	        	$path = 'database/migrations/' . $namespace;


	        	echo "<br>MIGRATION PATH:" . $path;
	        	echo Artisan::call('migrate', array('--path' => $path,'--force' => true,'--verbose' => true));
	        //	echo '<br>' . $result;
	        } else {
	        	Artisan::call('migrate', array('--force' => true));
	        }
	        echo '<br>Migration is done!';
	        echo '<br><a href="'.route('migrations').'">Go back</a>';
	    } catch (Exception $e) {
	        echo 'Caught exception: ',  $e->getMessage(), "\n";
	    };
	}
}
