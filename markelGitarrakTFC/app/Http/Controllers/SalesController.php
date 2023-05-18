<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ErabiltzaileChat;
use App\Models\Eskaintza;
use App\Models\Mezua;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function zuresalerosketak()
    {
        //return null if not logged in
        if (session('user') == null) {
            return view('login');
        }

        $user = User::where('email', session('email'))->first();
        $eskaintzak = Eskaintza::where('userId', $user['id'])->get();

        $gureerabiltzailechats = ErabiltzaileChat::where('userId', $user->id)->get();

        $clist = [];
        foreach ($gureerabiltzailechats as $key => $value) {
            array_push($clist, $value['chatId']);
        }

        $idlist = [];
        foreach ($eskaintzak as $key => $value) {
            array_push($idlist, $value['id']);
        }

        //Gure erabiltzaileak dituen chat denak
        $chats = Chat::whereIn('id', $clist)->get();

        //Guri eskainitako oferten chat lista
        $chatoffers = Chat::whereIn('eskaintzaId', $idlist)->get();

        $idlist = [];
        foreach ($chats as $key => $value) {
            array_push($idlist, $value['eskaintzaId']);
        }

        //Guk ditugun saltzeko edo erosteko eskaintza denak
        $salerosketaeskaintzak = Eskaintza::whereIn('id', $idlist)->get();

        //Gure salerosketan parte hartzen duten erabiltzile guztiak
        $chatsidlist = [];
        foreach ($chats as $key => $value) {
            array_push($chatsidlist, $value['id']);
        }

        $erabiltzailechatsdenak = ErabiltzaileChat::whereIn('chatId', $chatsidlist)->get();

        $erabiltzaileId = [];
        foreach ($erabiltzailechatsdenak as $key => $value) {
            array_push($erabiltzaileId, $value['userId']);
        }

        $erabiltzaileak = User::whereIn('id', $erabiltzaileId);



        return view("salerosketak", compact('user', 'erabiltzailechatsdenak', 'chats', 'chatoffers', 'salerosketaeskaintzak', 'erabiltzaileak'));
    }

    public function makeoffer(Request $request)
    {
        $user = User::where('email', session('email'))->first();
        $eskaintza = Eskaintza::where('id', $request->eskaintza)->first();

        $chat = new Chat();
        $chat->eskaintzaId = $eskaintza->id;
        $chat->save();

        $erabiltzaileChat = new ErabiltzaileChat();
        $erabiltzaileChat->userId = $user->id;
        $erabiltzaileChat->chatId = $chat->id;
        $erabiltzaileChat->save();

        $erabiltzaileChat = new ErabiltzaileChat();
        $erabiltzaileChat->userId = $eskaintza->userId;
        $erabiltzaileChat->chatId = $chat->id;
        $erabiltzaileChat->save();

        return redirect("/salerosketak");
    }

    public function offererakutsi(Request $request)
    {
        $id = $request->id;
        $user = User::where('email', session('email'))->first();
        $chat = Chat::where('id', $id)->first();
        $eskaintza = Eskaintza::where('id', $chat->eskaintzaId)->first();
        $erabiltzailechats =  Erabiltzailechat::where('chatId', $chat->id)->get();

        $mezuak = Mezua::where('chatId', $chat->id)->get();

        //Gu ez garen ofertako beste erabiltzailea jaso
        $besteuser = $erabiltzailechats->where('userId', '!=', $user->id)->first();
        $besteuser = User::where('id', $besteuser->userId)->first();


        return view('offer', compact('user', 'chat', 'eskaintza', 'besteuser', 'mezuak'));
    }

    public function mezuakjaso(Request $request)
    {
        $user = User::where('email', session('email'))->first();
        $chat = Chat::where('id', $request->chatId)->first();
        $eskaintza = Eskaintza::where('id', $chat->eskaintzaId)->first();
        $mezuak = Mezua::where('chatId', $request->chatId)->get();
        $besteuser = User::where('id', $request->besteuserid)->first();


        $html = "";

        //Ez badago mezurik
        if($mezuak->count() <= 0){
            return response()->json(array('mezuak' => $mezuak, 'html' => $html));
        }
        
        $mezua = $mezuak[$mezuak->count() - 1];
        if ($eskaintza->userId == $besteuser->id) {
            //Gu erosten gauden kasuan
            $txt = "<div id='mezuabox'>
                        <p>" . $mezua->textua . "</p>
                        <p>" . $mezua->data . "</p>
                    </div>";
        } else {
            //Gu saltzen gauden kasuan
            if($mezua->userId == $user->id){
                //Azken mezua gurea denean
                $txt = "<div id='mezuabox'>
                    <p>" . $mezua->textua . "</p>
                    <p>" . $mezua->data . "</p>
                </div>";
            }else{
                $txt = "<div id='mezuabox'>
                <p>" . $mezua->textua . "</p>
                <p>" . $mezua->data . "</p>
                <div><button id='btn_bai'>BAI</button><button id='btn_ez'>EZ</button></div>
            </div>";
                
            }
           
        }

        $html .= $txt;

        return response()->json(array('mezuak' => $mezuak, 'html' => $html));
    }

    public function mezuasortu(Request $request)
    {
        $chat = Chat::where('id', $request->chatId)->first();
        $user = User::where('email', session('email'))->first();

        $mezua = new Mezua();
        $mezua->userId = $user->id;
        $mezua->chatId = $chat->id;
        $mezua->textua = $request->prezioa."€ eskaintzen ditu";
        $mezua->data = date('Y-m-d H:i:s');
        $mezua->irakurrita = 0;
        $mezua->save();

        return response()->json(array('msg' => 'ondo sortu da mezua'));
    }

    public function ofertaez(Request $request){
        $chat = Chat::where('id', $request->chatId)->first();
        $user = User::where('email', session('email'))->first();
        $mezuak = Mezua::where('chatId', $request->chatId)->get();
        $besteuser = User::where('id', $request->besteuserid)->first();

        $azkenmezua = $mezuak[$mezuak->count()-1];
        $azkenmezua->irakurrita = 1;
        $azkenmezua->save();

        $mezua = new Mezua();
        $mezua->userId = $user->id;
        $mezua->chatId = $chat->id;
        $mezua->textua = $azkenmezua->textua."€-ko eskaintza baztertu du";
        $mezua->data = date('Y-m-d H:i:s');
        $mezua->irakurrita = 0;
        $mezua->save();


        return response()->json(array('msg' => 'oferta ukatu da'));
    }

    public function ofertaonartu(Request $request){
        $chat = Chat::where('id', $request->chatId)->first();
        $user = User::where('email', session('email'))->first();
        $mezuak = Mezua::where('chatId', $request->chatId)->get();
        $besteuser = User::where('id', $request->besteuserId)->first();

        //Azken mezua irakurrita bezala markatu
        $azkenmezua = $mezuak[$mezuak->count()-1];
        $azkenmezua->irakurrita = 1;
        $azkenmezua->save();

        //eskaintzari prezioa aldatu eta eroslearen id-a jarri
        $prezioa = filter_var($azkenmezua->textua, FILTER_SANITIZE_NUMBER_INT);
        $eskaintza = Eskaintza::where('id', $chat->eskaintzaId)->first();
        $eskaintza->prezioa = $prezioa;
        $eskaintza->erosleId = $besteuser->id;
        $eskaintza->save();

        //Azken mezu bat sortu
        $mezua = new Mezua();
        $mezua->userId = $user->id;
        $mezua->chatId = $chat->id;
        $mezua->textua = $prezioa."€-ko eskaintza onartu du";
        $mezua->data = date('Y-m-d H:i:s');
        $mezua->irakurrita = 0;
        $mezua->save();

        return response()->json(array('msg' => 'oferta onartu da'));
    }
}
