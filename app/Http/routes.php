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

//Home
Route::get('/', 'HomeController@index');
Route::get('/upload', function() {
	if(Auth::check()){
		return view('upload');
	} else{
		return redirect("login");
	}
});

//Login
Route::get('login', function(){
	return view('login');
});

Route::get('logout', function(){
	Auth::logout();
	return redirect(URL::previous());
});

Route::resource('u', 'UserController');
Route::post('u/upload', 'UserController@upload');
Route::post('u/ldap', 'UserController@ldap');
Route::get('profile', 'UserController@profile');
Route::get('profile/groups', 'UserController@groups');

//Getter
Route::get('contacts', 'UserController@getContacts');
Route::post('exe/contact', 'HomeController@contact');

Route::resource('d', 'DropController');
Route::get('d/{id}/download', 'DropController@download');
Route::post('d/{id}/share', 'DropController@share');

Route::resource('dt', 'DropTagController');

Route::resource('f', 'FileController');
Route::get('f/{id}/download', 'FileController@download');
Route::get('f/{id}/getComments', 'FileController@getComments');

Route::resource('fc', 'FileCommentController');

//Route::get('home', 'HomeController@index');

Route::get('help', function(){
	return view('help');
});

/*
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
*/