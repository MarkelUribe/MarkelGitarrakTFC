<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ErabiltzaileChat;
use App\Models\Eskaintza;
use App\Models\EskaintzaMota;
use App\Models\Like;
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

        $eskaintzak = Eskaintza::where([['userId', $user['id']]])->get();
        $eskaintzak = $eskaintzak->where('erosleId', '==', Null);

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

            $eskaintzajabea = User::where('id', $eskaintza->userId)->first();

            $mota = EskaintzaMota::where('id', $eskaintza->motaId)->first();

            $like = LIke::where([['eskaintzaId', '=', $id], ['userId', '=', $user->id]])->first();

            if ($like === null) {
                $likeimg = asset('storage/'. 'icon/unliked.png' );
            } else {
                $likeimg = asset('storage/'. 'icon/liked.png' );
            }

            if ($eskaintza === null) {
                abort(404);
            }
            $latlon = [0, 0];
            try {
                $latlon = $this->kokapenaHelbidetikJaso($eskaintza->kokapena);
            } catch (\Exception $e) {
                $latlon = [0, 0];
            }

            $distantzia = $this->helbideenArtekoDistantzia($user->kokapena, $eskaintza->kokapena);

            //oferta hartu duen jakiteko
            $erabiltzailechats = ErabiltzaileChat::where('userId', $user->id)->get();
            $chatIds = [];
            foreach ($erabiltzailechats as $key => $value) {
                array_push($chatIds, $value['chatId']);
            }
            $chats = Chat::whereIn('id', $chatIds);

            if($chats->where('eskaintzaId', $eskaintza->id)->count() > 0) {
                $ofertahartuta=true;
            }else{
                $ofertahartuta=false;
            }


            return view("eskaintza", compact('eskaintza', 'user', 'mota', 'latlon', 'eskaintzajabea', 'distantzia', 'likeimg', 'ofertahartuta'));
        } catch (\Exception $e) {
            return redirect("/login");
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
                'kokapena' => $request->kokapena,
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

    //Helbide bat eman eta latitude eta longitude balioak itzultzen ditu funtzio honek.
    function kokapenaHelbidetikJaso($address)
    {

        $url = 'https://api.geoapify.com/v1/geocode/search?text=' . str_replace(' ', '%20', $address) . '&format=json&apiKey=86c99c7541ee4dd681d48fef26c1d135';

        $ch = curl_init();
        $timeout = 5;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $result = curl_exec($ch);
        curl_close($ch);


        $response = json_decode($result);
        if (!empty($response->results)) {
            return [$response->results[0]->lat, $response->results[0]->lon];
            //return $response->results;
        } else {
            return [0, 0];
        }
    }


    //Bi helbideen arteko distantzia kilometrotan kalkulatzen du
    function helbideenArtekoDistantzia($a1, $a2)
    {
        try {
            $loc1 = $this->kokapenaHelbidetikJaso($a1);
            $loc2 = $this->kokapenaHelbidetikJaso($a2);

            $lat1 = deg2rad($loc1[0]);
            $lon1 = deg2rad($loc1[1]);
            $lat2 = deg2rad($loc2[0]);
            $lon2 = deg2rad($loc2[1]);

            $deltaLat = $lat2 - $lat1;
            $deltaLon = $lon2 - $lon1;

            $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1) * cos($lat2) * sin($deltaLon / 2) * sin($deltaLon / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distKm = 6371 * $c;

            $distKm = round($distKm, 2) . 'km';

            return $distKm;
        } catch (\Exception $e) {
            return 'error';
        }
    }

    function likeorunlike(Request $request)
    {
        try {
            $user = User::where('email', session('email'))->first();

            $like = Like::where([['eskaintzaId', '=', $request->eskaintzaId], ['userId', '=', $user->id]])->first();
            if ($like === null) {
                $like = new Like();
                $like->userId = $user->id;
                $like->eskaintzaId = $request->eskaintzaId;
                $like->save();
                $img = asset('storage/'. 'icon/liked.png' );
                return response()->json(array('message' => 'Eskaintza likeatu da', 'img' => $img ));
            } else {
                $like->delete();
                $img = asset('storage/'. 'icon/unliked.png' );
                return response()->json(array('message' => 'Eskaintza unlikeatu da', 'img' => $img));
            }
        } catch (\Exception $e) {
            return response()->json(array('message' => $e,));
        }
    }

    public function likeakerakutsi(){
        try {
            $user = User::where('email', session('email'))->first();
            $likes = Like::where([['userId', '=', $user->id]])->get();
            $likeids = array();
            foreach ($likes as $key => $value) {
                array_push($likeids, $value->eskaintzaId);
            }

            $eskaintzak = Eskaintza::whereIn('id', $likeids)->get();
            $eskaintzak = $eskaintzak->where('erosleId','==', Null);

            return view("likes", compact('eskaintzak'));


        } catch (\Exception $e) {
            return redirect('/login');
        }
    }
}
