<?php

namespace App\Http\Controllers\admon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index(){
        return view('admon.Caja');
    }

    public function store(Request $request){
        foreach($request as $key=>$value){
            dd("Caja2",$request);
            ##### Procesa cada una de las ventas
            if( preg_match("/caja_/", $key) and $value > 0 ) {
                #$key=preg_replace("/caja_/","",$key);
                if($key['caja_operacion']=="egreso"){

                }
            /* CajaModel::create([
                    'caja_act'=>'1',
                    'caja_caja'=>$precio*$value,
                    'caja_responsable'=>auth()->user()->id,
                    'caja_usrid'=>$usr->id,
                    'caja_tipo'=>'venta',
                    'caja_descripcion'=>$value.",".$prod->id,
                    'caja_observaciones'=>$value.",".$prod->id.",".$prod->gpo.",".$prod->nombre.",".$prodSabor,
                ]);*/
            }
        }
    }
}
