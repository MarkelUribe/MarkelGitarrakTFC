<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Erabiltzailea extends Controller
{
    public function index()
    {
        if (session('user') == null) {
            return view('login');
        }

        $user = User::where('email', session('email'))->first();

        $latlon = app('App\Http\Controllers\EskaintzaController')->kokapenaHelbidetikJaso($user->kokapena);

        return view('erabiltzailea', [
            'user' => $user,
            'latlon' => $latlon,
        ]);
    }

    public function argAldatu(Request $request)
    {
        //store file
        $path = $request->file('file')->store('public/profilepics');

        //remove previous ppic if exists
        if (Storage::exists('public/' . session('img'))) {
            Storage::delete('public/' . session('img'));
        }

        //change path for storage, remove public/ so it displays in the view
        $path = str_replace('public/', '', $path);

        //find user and store path
        $user = User::where('email', session('email'))->first();
        $user->argazkia = $path;
        $user->updated_at = now();
        $user->save();

        //store path in session
        $request->session()->put('img', $path);

        return redirect()->back()->with('message', 'Argazkia arrakastaz igo da!');
    }

    public function erabiltzailekokapenaaldatu(Request $request)
    {
        $user = User::where('email', session('email'))->first();

        $user->kokapena = $request->address;
        $user->updated_at = now();
        $user->save();

        return response()->json(array('address' => $request->address));

    }
}
