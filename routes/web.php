<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/user', function () {
    return 111;
    return view('welcome');
});
Route::get('/users', 'UserController@index');


Route::get('/quick-clean', function () {
	\Artisan::call('config:cache');
	\Artisan::call('view:clear');
	\Artisan::call('route:clear');
	\Artisan::call('event:clear');
	\Artisan::call('cache:clear');
	\Artisan::call('optimize:clear');
	die("cache cleared");
});
