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
    return view('info');
});
// Route::get('{select}', 'RTCController@select');

Route::get('admin',function(){
    return view('omfirda.admin');
});
Route::get('client',function(){
    return view('omfirda.clinet');
});
Route::post('saveM','RTCController@saveM');
Route::get('checkM','RTCController@checkM');

Route::get('/',function(){
    return view('aaa.a');
});
Route::get('b','RTCController@b');