<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Carbon\Carbon;
use App\SalesInteraction;

Route::get('game/{id}/toggle_challenge', array('as' => 'game.toggle_challenge', 'uses' => 'GameController@toggle_challenge'));
Route::get('game/{id}/deactivate', array('as' => 'game.deactivate', 'uses' => 'GameController@deactivate'));
Route::get('game/{id}/delete', array('as' => 'game.delete', 'uses' => 'GameController@delete'));
Route::get('game/datatable', array('as' => 'game.datatable', 'uses' => 'GameController@datatable'));
Route::resource('game', 'GameController');


Route::get('challenge/datatable', array('as' => 'challenge.datatable', 'uses' => 'ChallengeController@datatable'));
Route::resource('challenge', 'ChallengeController');
Route::resource('player', 'PlayerController');

Route::get('round/{round_id}/next', array('as' => 'round.next', 'uses' => 'RoundController@next'));
Route::post('round/{round_id}/submit_video', array('as' => 'round.submit_video', 'uses' => 'RoundController@submit_video'));
Route::get('bet/{round_id}/set_winner/{winner_id}', array('as' => 'round.set_winner', 'uses' => 'RoundController@set_winner'));
Route::resource('round', 'RoundController');

Route::get('bet/{id}/delete', array('as' => 'bet.delete', 'uses' => 'BetController@delete'));
Route::resource('bet', 'BetController');



Route::get('/test', function () {

});

Route::get('/', function () {
    if(Auth::check())
    {
        return redirect()->route('home');
    } else {
        return redirect()->action('Auth\AuthController@login');
    }
});

Route::auth();


Route::get('/home', ['as' => 'home', 'uses'=> 'HomeController@index']);

Route::get('load_comments', array('as' => 'comments.load', 'uses' => 'CommentController@CommentFeed'));
Route::resource('comments', 'CommentController');

Route::get('attachments/{id}/delete', ['as' => 'attachments.delete', 'uses' => 'AttachmentController@delete']);
Route::resource('attachments', 'AttachmentController');


Route::get('users/update_role', array('as' => 'user.role_update', 'uses' => 'UserController@updateRoles'));
Route::post('user/upload_avatar', ['as' =>'user.upload_avatar', 'uses' =>'UserController@upload_avatar']);
Route::get('user/json', ['as' =>'user.json', 'uses' =>'UserController@json']);
Route::post('user/{id}/update_phone', ['as' =>'user.update_phone', 'uses' =>'UserController@update_phone']);
Route::resource('user', 'UserController');
Route::resource('roles', 'RoleController');



Route::get('/updateapp', function()
{
    \Artisan::call('dump-autoload');
    echo 'dump-autoload complete';
});


Route::get('/hostname', function()
{
    return gethostname();
});

Route::get('/env', function()
{
    return App::environment();

});

Route::get('/db', function()
{
    // Test database connection
    try {
        DB::connection()->getPdo();
    } catch (\Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
});


//RUN MIGRATIONS
Route::get('/install',  array('as' => 'install', function()
{

    try {
        echo 'Let`s start';
        echo '<br>init migrate:install...';
        Artisan::call('migrate', array('--force' => true));
        echo '<br>Migration is done!';
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    };
}
));



Route::group(['namespace' => 'Admin'], function() {
    Route::get('/migrations/install', array('as'=>'migrations.install', 'uses' => 'MigrationController@install'));
    Route::get('/migrations', array('as'=>'migrations', 'uses' => 'MigrationController@index'));
    Route::get('admin/roles/update_permissions', array('as'=>'roles.update_permissions', 'uses' => 'RoleController@update_permissions'));
    Route::resource('admin/roles', 'RoleController');
    Route::resource('admin/permissions', 'PermissionController');
});
