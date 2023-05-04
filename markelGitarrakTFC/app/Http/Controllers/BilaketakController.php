<?php

namespace App\Http\Controllers;

use App\Models\Eskaintza;
use App\Models\EskaintzaMota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BilaketakController extends Controller
{
    public function bilatuIndex()
    {
        $eskaintzamotak = EskaintzaMota::all();
        return view("bilatu", compact('eskaintzamotak'));
    }

    public function eskaintzakfiltratu(Request $request)
    {
       if($request->mota !== null or $request->mota != ""){
        $eskaintzak = Eskaintza::where([['izena','like','%' . $request->bilaketa . '%'],['motaId','=', $request->mota]])->orWhere([['azalpena','like', '%' . $request->bilaketa . '%'],['motaId','=', $request->mota]])->get();
       }else{
        $eskaintzak = Eskaintza::where([['izena','like','%' . $request->bilaketa . '%']])->orWhere([['azalpena','like', '%' . $request->bilaketa . '%']])->get();
       }

        $htmlesk = "";
        foreach ($eskaintzak as $esk) {

            $htmlesk .= ' <a href="eskaintza/' . $esk->id . '" style="color: inherit;text-decoration: none;">
                <div class="eskaintzabox">';

            $img_arr = array_filter(explode(",", $esk->argazkiak), fn ($value) => !is_null($value));
            foreach ($img_arr as $key => $newarr) {
                if (trim($newarr) === '') {
                    unset($img_arr[$key]);
                }
            }
            $htmlesk .= "
                <img src='" . asset('storage/' . trim($img_arr[1])) . "'>
                <h5> $esk->prezioa €</h5>
                <p> $esk->izena </p>
            </div>
            </a>";
        }

        return response()->json(array('eskaintzak' => $htmlesk));
    }
}
