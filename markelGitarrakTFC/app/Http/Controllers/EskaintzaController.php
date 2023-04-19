<?php

namespace App\Http\Controllers;

use App\Models\Eskaintza;
use App\Models\EskaintzaMota;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\Input;


class EskaintzaController extends Controller
{
    public function zureeskaintzak()
    {
        //return null if not logged in
        if (session('user') == null) {
            return view('login');
        }

        $user = User::where('email', session('email'))->first();

        $eskaintzak = Eskaintza::where('userId', $user['id'])->get();

        return view("zureeskaintzak", compact('eskaintzak'));
    }

    public function eskaintzaberria()
    {
        //return with all eskaintza motak
        $motak = EskaintzaMota::all()->sortBy("mota");

        return view("eskaintzaberria", compact('motak'));
    }

    public function sortueskaintza(Request $request)
    {
        $user = User::where('email', session('email'))->first();

        $eskaintza = new Eskaintza();
        $eskaintza->izena = $request['izena'];
        $eskaintza->azalpena = $request['azalpena'];
        $eskaintza->motaId = $request['mota'];
        $eskaintza->prezioa = $request['prezioa'];
        $eskaintza->estatua = $request['egoera'];
        $eskaintza->argazkiak = $request['argazkiak'];
        $eskaintza->userId = $user->id;

        $eskaintza->save();

        return redirect("/zureeskaintzak");
    }


    public function argazkiagehitu(Request $request)
    {
        $file = $request->file('file');
        $path = $file->store('public/productimages');

        $argazkiak = $request->argazkiak;

        //change path for storage, remove public/ so it displays in the view
        $path = str_replace('public/', '', $path);

        $argazkiak .= ', ' . $path;


        return response()->json(array('argazkiak' => $argazkiak, 'path' => $path));
    }

    public function argazkiakendu(Request $request)
    {
        Storage::delete('public/' . $request->realpath);
        return response()->json(array('path' => $request['path'], 'realpath' => $request['realpath'],));
    }

    public function eskaintzaerakutsi(Request $request)
    {
        try {
            $id = $request->id;
            $user = User::where('email', session('email'))->first();
            $eskaintza = Eskaintza::where('id', $id)->first();

            return view("eskaintza", compact('eskaintza'));
            
        } catch (\Exception $e) {
            return redirect("/");
        }
    }
}
