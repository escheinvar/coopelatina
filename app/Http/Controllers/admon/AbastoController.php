<?php

namespace App\Http\Controllers\admon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AbastosModel;
use App\Models\CajaModel;
use App\Models\ProductosModel;
use DateTime;

use Ramsey\Collection\Map\AbstractMap;

class AbastoController extends Controller
{
    public function index(){
        return view('admon.Abasto');
    }


    public function store(request $request){
        #dd($request->all());
        $caja=$request['FuenteDelPago'];
        foreach($request->all() as $key=>$value){
            if (preg_match("/com.:.*/",$key)  AND !is_null($value)){
                # dd($key,$value);
                $idDelProducto=AbastosModel::where('aba_producto',$key)->value('aba_prodid');
                $PedidosPlaneados=AbastosModel::where('aba_producto',$key)->value('aba_listas_cantpeds');
                $Costo=ProductosModel::where('id',$idDelProducto)->value('costo');
                if($value < $PedidosPlaneados ){$faltante='1';}else{$faltante='0';}
                AbastosModel::where('aba_producto',"$key")->update([
                    'aba_abasto'=>'1',
                    'aba_abasto_cant'=>"$value",
                    'aba_abasto_date'=>new DateTime("today"),
                    'aba_faltante'=>$faltante,
                    'aba_pagado'=>'1',
                    'aba_pagamonto'=>$Costo * $value,
                ]);

                $IdDeQuienRegistra= auth()->user()->id;
                $Proveedor=ProductosModel::where('id',$idDelProducto)->value('proveedor');

                if ( $caja == 'caja' ){$a=($Costo*$value)*-1; $b='0';$c='0';
                }elseif($caja=='teso' ){$a='0'; $b=($Costo*$value)*-1;$c='0';
                }elseif($caja=='banco'){$a='0'; $b='0';$c=($Costo*$value)*-1;}
                CajaModel::insert([
                    'caja_act'=>'1',
                    'caja_caja'=>$a,
                    'caja_teso'=>$b,
                    'caja_banco'=>$c,
                    'caja_responsable'=> $IdDeQuienRegistra,
                    'caja_usrid'=>'0',
                    'caja_tipo'=>'Pago Abasto',
                    'caja_descripcion'=>$Proveedor,
                    'caja_observaciones'=>$value.' pzas de id '.$idDelProducto.' a $'.$Costo,
                ]);
            }
        }
        return view('admon.Abasto');
    }
}
