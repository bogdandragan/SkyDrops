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
Route::get('/home', 'HomeController@home');

Route::get('/upload', 'HomeController@upload');

Route::get('/shop', 'ShopController@index');
Route::post('/shop/pay', 'ShopController@pay');
Route::post('/shop/startPayment', 'ShopController@startPayment');
Route::get('/PayPalCancel', 'ShopController@cancelPayment');
Route::get('/PayPalSuccess', 'ShopController@successPayment');



Route::post('/PayPal_IPN', 'ShopController@payPalIPN');

//Login
Route::get('login', function(){
	return view('login');
});

//Register
Route::get('auth/register', function(){
	return view('register');
});
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('auth/confirm/{code}', 'Auth\AuthController@getConfirm');


Route::get('logout', function(){
	Auth::logout();
	return redirect("/");
});

Route::get('auth/restore', function(){
	return view('restore');
});

Route::resource('u', 'UserController');
Route::post('u/upload', 'UserController@upload');
Route::post('u/upload/save', 'UserController@saveUpload');
Route::post('u/getFECost', 'UserController@getFECost');
Route::post('u/upload/empty', 'UserController@uploadEmpty');

Route::post('u/addFile', 'UserController@addFile');
Route::post('u/addFile/save', 'UserController@saveAddFile');
Route::post('u/addFile/deleteTmp', 'UserController@deleteTmpAddFile');

Route::post('u/ldap', 'UserController@ldap');
Route::post('u/restore', 'UserController@restorePassword');
Route::get('u/restoreConfirm/{code}', 'UserController@restorePasswordConfirm');
Route::post('u/newPassword', 'UserController@setNewPassword');
Route::get('coinStatistics', 'UserController@coinStatistics');

Route::get('getAvailableCoins', 'UserController@getAvailableCoins');

Route::get('profile', 'UserController@profile');
Route::get('profile/groups', 'UserController@groups');

//Getter
Route::get('contacts', 'UserController@getContacts');
Route::post('exe/contact', 'HomeController@contact');

Route::resource('d', 'DropController');
Route::get('d/{id}/download', 'DropController@download');
Route::get('d/{id}/downloadZip', 'DropController@downloadZip');
Route::get('d/{id}/sharedForUpload', 'DropController@sharedForUpload');
Route::post('d/{id}/share', 'DropController@share');
Route::post('d/{id}/updateValidity', 'DropController@updateValidity');
Route::post('d/{id}/updateTitle', 'DropController@updateTitle');
Route::post('d/{id}/shareForUpload', 'DropController@shareForUpload');

Route::resource('dt', 'DropTagController');

Route::resource('f', 'FileController');
Route::get('f/{id}/download', 'FileController@download');
Route::get('f/{id}/remove', 'FileController@remove');
Route::get('f/{id}/getComments', 'FileController@getComments');

Route::resource('fc', 'FileCommentController');

//Route::get('home', 'HomeController@index');

Route::get('help', function(){
	return view('help');
});

Route::get('admin/dashboard', 'AdminController@adminDashboard');
Route::get('admin/statistic', 'AdminController@adminStatistic');
Route::get('admin/statistic/getDrops', 'AdminController@getDropsStatistic');
Route::get('admin/statistic/getUserAgent', 'AdminController@getUserAgentStatistic');


Route::get('landing', function(){
	return view('landing');
});

/*
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
*/
