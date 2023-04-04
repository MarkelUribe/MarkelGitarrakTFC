<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuth;
use App\Http\Controllers\Erabiltzailea;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post("userlogin",[UserAuth::class, 'userLogin']);
Route::post("userRegister",[UserAuth::class, 'userRegister']);
Route::get("erabiltzailea",[Erabiltzailea::class, 'index']);

Route::post("erabiltzaileimgaldatu",[Erabiltzailea::class, 'argAldatu']);

Route::view("login", 'login');
Route::view("register", 'register');

Route::get('/login', function(){
    if(session()->has('user')){
        return redirect('welcome');
    }
    return view('login');
});
Route::get('/logout', function(){
    if(session()->has('user')){
        session()->pull('user');
    }
    return redirect('login');
});


