<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UserAuth extends Controller
{
    function userLogin(Request $req)
    {

        $credentials = $req->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $user = User::where('email',$req['email']) -> first();
            $req->session()->put('user', $user['name']);
            $req->session()->put('email', $user['email']);
            $req->session()->put('img', $user['argazkia']);
            echo session('user');
            return redirect()->intended('');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    function userRegister(Request $req)
    {
        $validatedData = $req->validate([
            'izena' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|string|min:8|confirmed',
            'abizena' => 'required|string|max:255',
        ]);

        $user = new User();
        $user->password = Hash::make($validatedData['password']);
        $user->email = $validatedData['email'];
        $user->name = $validatedData['izena'];
        $user->surname = $validatedData['abizena'];
        $user->save();

        $data = $req->input();
        $req->session()->put('user', $user['name']);
        $req->session()->put('email', $user['email']);
        echo session('user');
        return redirect('/');
    }
}
