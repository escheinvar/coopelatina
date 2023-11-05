<?php

namespace App\Http\Controllers\admon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AbastosModel;
use DateTime;

class AbastoController extends Controller
{
    public function index(){
        return view('admon.Abasto');
    }


    public function store(request $request){
        $ganon=$request->ganon;
        $valor=$request[$ganon];

        AbastosModel::where('aba_producto',"$ganon")->update([
            'aba_abasto'=>'1',
            'aba_abasto_cant'=>"$valor",
            'aba_abasto_date'=>new DateTime("today"),
        ]);
        return view('admon.Abasto');
    }
}
