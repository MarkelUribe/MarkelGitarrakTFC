<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuth;
use App\Http\Controllers\Erabiltzailea;
use App\Http\Controllers\Eskaintza;
use App\Http\Controllers\EskaintzaController;

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
        return view('welcome');
    }
    return view('login');
});
Route::get('/logout', function(){
    if(session()->has('user')){
        session()->flush();
    }
    return redirect('login');
});

Route::get('eskaintza/{id}', [EskaintzaController::class, 'eskaintzaerakutsi']);

Route::get("zureeskaintzak",[EskaintzaController::class, 'zureeskaintzak']);
Route::get("eskaintzaberria",[EskaintzaController::class, 'eskaintzaberria']);
Route::post("sortueskaintza",[EskaintzaController::class, 'sortueskaintza']);
Route::post('argazkiagehitu',[EskaintzaController::class, 'argazkiagehitu']);
Route::post('argazkiakendu',[EskaintzaController::class, 'argazkiakendu']);

