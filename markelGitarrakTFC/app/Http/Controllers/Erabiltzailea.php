<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class Erabiltzailea extends Controller
{
    public function index()
    {
        if (session('user') == null) {
            return view('login');
        }

        $user = User::where('email', session('email'))->first();
        return view('erabiltzailea', [
            'user' => $user,
        ]);
    }

    public function argAldatu(Request $request)
    {
        $path = $request->file('file')->store('profilepics');



        
        $user = User::where('email', session('email'))->first();
        $user->argazkia = $path;
        $user->updated_at = now();
        $user->save();

        //$user->update([
        //    'argazkia' => $path,
        //    'updated_at' => now()
        //]);

        return redirect()->back()->with('message', 'Argazkia arrakastaz igo da!');
    }
}
