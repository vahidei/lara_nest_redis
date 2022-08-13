<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
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

Route::get('/test', function(){
	echo 'safsafa';
});

Route::get('/publish', function () {
    print_r(Redis::publish('test-channel', json_encode([
        'name' => 'Vahid',
        'family' => 'Esmaeili'
    ])));
});
