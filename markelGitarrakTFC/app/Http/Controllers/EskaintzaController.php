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
        $eskaintza->kokapena = $request['kokapena'];
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

    public function argazkiagehitualdaketan(Request $request)
    {
        $file = $request->file('file');
        $path = $file->store('public/productimages');

        $argazkiak = $request->argazkiak;

        //change path for storage, remove public/ so it displays in the view
        $path = str_replace('public/', '', $path);

        $argazkiak .= ', ' . $path;



        DB::table('eskaintzak')
            ->where('id', $request->eskaintzaid)  // find your user by their email
            ->limit(1)  // optional - to ensure only one record is updated.
            ->update(array(
                'argazkiak' => $argazkiak,
            ));


        return response()->json(array('argazkiak' => $argazkiak, 'path' => $path));
    }

    public function argazkiakendualdaketan(Request $request)
    {
        try {
            $eskaintza = Eskaintza::where('id', $request->eskaintzaid)->first();

            $eskaintza->argazkiak = str_replace(', ' . $request->realpath, '', $eskaintza->argazkiak);
            $eskaintza->save();

            Storage::delete('public/' . $request->realpath);
            return response()->json(array('path' => $request['path'], 'realpath' => $request['realpath'],));
        } catch (\Exception $e) {
            return redirect("/");
        }
    }

    public function eskaintzaerakutsi(Request $request)
    {
        try {
            $id = $request->id;
            $user = User::where('email', session('email'))->first();
            $eskaintza = Eskaintza::where('id', $id)->first();

            $mota = EskaintzaMota::where('id', $eskaintza->motaId)->first();

            if ($eskaintza === null) {
                abort(404);
            }


            return view("eskaintza", compact('eskaintza', 'user', 'mota'));
        } catch (\Exception $e) {
            return redirect("/");
        }
    }

    public function eskaintzaaldatu(Request $request)
    {
        //return null if not logged in
        if (session('user') == null) {
            return view('login');
        }
        $id = $request->id;
        $user = User::where('email', session('email'))->first();
        $eskaintza = Eskaintza::where('id', $id)->first();
        $motak = EskaintzaMota::all()->sortBy("mota");

        $argazkiak = array_filter(preg_split("/\,/", $eskaintza['argazkiak']));
        $argazkistring = $eskaintza['argazkiak'];

        if ($eskaintza === null) {
            abort(404);
        }

        if ($eskaintza->userId != $user->id) {
            abort(404);
        }

        return view("eskaintzaaldatu", compact('eskaintza', 'user', 'motak', 'argazkiak', 'argazkistring'));
    }

    public function submiteskaintzaaldatu(Request $request)
    {
        $user = User::where('email', session('email'))->first();
        $eskaintza = Eskaintza::where('id', $request->id)->first();

        if ($eskaintza->userId != $user->id) {
            abort(404);
        }

        DB::table('eskaintzak')
            ->where('id', $request->id)  // find your user by their email
            ->limit(1)  // optional - to ensure only one record is updated.
            ->update(array(
                'izena' => $request->izena,
                'motaId' => $request->mota,
                'azalpena' => $request->azalpena,
                'prezioa' => $request->prezioa,
                'estatua' => $request->egoera,
                'argazkiak' => $request->argazkiak,
            ));

        return redirect("/zureeskaintzak");
    }

    public function eskaintzaezabatu(Request $request)
    {
        //return null if not logged in
        if (session('user') == null) {
            return view('login');
        }
        $id = $request->id;
        $user = User::where('email', session('email'))->first();
        $eskaintza = Eskaintza::where('id', $id)->first();

        if ($eskaintza === null) {
            abort(404);
        }

        if ($eskaintza->userId != $user->id) {
            abort(404);
        }


        $img_arr = array_filter(explode(",", $eskaintza->argazkiak), fn ($value) => !is_null($value));
        foreach ($img_arr as $key => $newarr) {
            if (trim($newarr) === '') {
                unset($img_arr[$key]);
            }
        }
        $img_arr = array_values($img_arr);
        foreach ($img_arr as $key => $value) {
            Storage::delete('public/' . trim($value));
        }

        $eskaintza->delete();
        return redirect("/zureeskaintzak");
    }
}
