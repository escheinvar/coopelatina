<?php

namespace App\Http\Controllers\admon;

use App\Http\Controllers\Controller;
use App\Models\CajaModel;
use App\Models\EnvasesModel;
use App\Models\FoliosProdsModel;
use App\Models\ProductosModel;
use App\Models\User;
use Illuminate\Http\Request;

class CajaVentasController extends Controller
{
    public function index(){
        return view('admon.CajaVentas');
    }

    public function store(request $request){
        ##### Obtiene datos del usuario
        if($request->all('usuario') > 0){
            $usr=User::where('id',$request->usr)->first();
        }
        $salida=[];
        #dd($request->all());
        foreach($request->all() as $key=>$value){
            ##### Procesa cada una de las ventas
            if( preg_match("/venta_/", $key) and $value > 0 ) {
                
                $prod=preg_replace("/venta_/","",$key);
                $prodId=preg_replace("/@-.*/","",$prod);
                $prodSabor=preg_replace("/.*@-/","",$prod);
                
                ##### Obtiene datos del producto
                $prod=ProductosModel::where('id',$prodId)->first();
                if($usr->estatus == 'act'){
                    $precio = $prod->precioact;
                }else{
                    $precio = $prod->precioreg;
                }
                CajaModel::create([
                    'caja_act'=>'1',
                    'caja_caja'=>$precio*$value,
                    'caja_responsable'=>auth()->user()->id,
                    'caja_usrid'=>$usr->id,
                    'caja_tipo'=>'venta',
                    'caja_descripcion'=>$value.",".$prod->id,
                    'caja_observaciones'=>$value.",".$prod->id.",".$prod->gpo.",".$prod->nombre.",".$prodSabor,
                ]);
                $salida['venta']=['cant'=>$value,'precio'=>$precio,'id'=>$prod->id,'nombre'=>$prod->gpo." ".$prod->nombre,'sabor'=>$prodSabor];
                #dd($request->all(),$prodId,$prodSabor, $request->all('usuario'), $usr);
            }
            ##### Procesa cada una de las ventas
            if( preg_match("/envase_/", $key) and $value > 0 ) {
                $env=preg_replace("/envase_/","",$key);
                
                ##### Obtiene datos del envase
                $envase=EnvasesModel::where('fco_id',$env)->first();
                CajaModel::create([
                    'caja_act'=>'1',
                    'caja_caja'=>$envase->fco_costo * $value,
                    'caja_responsable'=>auth()->user()->id,
                    'caja_usrid'=>$usr->id,
                    'caja_tipo'=>'envase',
                    'caja_descripcion'=>$value.",".$envase->fco_id,
                    'caja_observaciones'=>$value.",".$envase->fco_id.",".$envase->fco_nombre.",".$envase->fco_costo,
                ]);
                $salida['envase']=['cant'=>$value,'precio'=>$envase->fco_costo,'id'=>$envase->fco_id,'nombre'=>$envase->fco_nombre];
                #dd($envase);
            }

            
            ##### Procesa cada una de las ventas
            if( preg_match("/devuelve_/", $key) and $value < 0 ) {
                $dev=preg_replace("/devuelve_/","",$key);
                
                ##### Obtiene datos del pedido a regresar
                $pedido=FoliosProdsModel::where('ped_id',$dev)->first();
                ##### Cambia caja
                CajaModel::create([
                    'caja_act'=>'1',
                    'caja_caja'=>$value,
                    'caja_responsable'=>auth()->user()->id,
                    'caja_usrid'=>$usr->id,
                    'caja_tipo'=>'devolPedido',
                    'caja_descripcion'=>$value.",".$pedido->ped_id,
                    'caja_observaciones'=>$value.",".$pedido->ped_id.",".$pedido->ped_producto.",".$pedido->ped_usrid,
                ]);
                $salida['devuelve']=['cant'=>'1','precio'=>$value,'id'=>$pedido->ped_id,'producto'=>$pedido->ped_producto,'usuario'=>$pedido->ped_usrid];
                ##### Cambia registro de pedido
                FoliosProdsModel::where('ped_id',$dev)->update([
                    'ped_entregado'=>'1',
                ]);
            }
        }
        return view('admon.CajaVentas',['salida'=>$salida, 'request'=>$request,]);
    }
}
