<?php

use Illuminate\Support\Facades\Route;

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

//Route::get('/pay', 'PayOrderController@store');
Route::get('/migrate', function() {
    $status = Artisan::call('migrate');
    return '<h1>Migrations Finished</h1>';
});
Route::get('/passport-install', function() {
    $status = Artisan::call('passport:install');
    return '<h1>Passport Installed</h1>';
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
