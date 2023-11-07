<?php

namespace App\Http\Controllers\admon;

use App\Models\FoliosModel;
use Illuminate\Http\Request;
use App\Models\ProductosModel;
use App\Models\FoliosProdsModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;


class ListasAbastoController extends Controller
{
    public function index(){
        return view('admon.ListasAbasto');
    }


    public function store(Request $request){

        $mes=session('ProxCom2date')['mes'] ; $anio=session('ProxCom2date')['anio'];
        foreach($request->all() as $key=>$value){
            if(preg_match("/com[1-2]:tien_.*/",$key) AND $value >= 0) {
                $comanda=preg_replace("/:.*/","",$key); 
                $prodId=preg_replace("/.*:tien_/","",$key); $prodId=preg_replace("/@.*/","",$prodId);
                $sabor=preg_replace("/.*@-/","",$key);

                $FolioCoope=FoliosModel::where('fol_act','1')
                    ->where('fol_anio',$anio)    
                    ->where('fol_mes',$mes)
                    ->where('fol_usrid','0')
                    ->value('fol_id');
                
                $producto=ProductosModel::where('id',$prodId)->first();
                if($producto->gpo == $producto->nombre){$NombreFin=$producto->gpo;}else{$NombreFin=$producto->gpo." ".$producto->nombre;}
                if($value > '0'){
                    FoliosProdsModel::updateOrCreate(['ped_act'=>'1', 'ped_folio'=>$FolioCoope, 'ped_producto'=>$comanda.':'.$prodId.'@'.$sabor ],[
                        'ped_act'=>'1', 
                        'ped_folio'=>$FolioCoope, 
                        'ped_entregado'=>'0',
                        'ped_entrega'=>$comanda, 
                        'ped_producto'=>$comanda.':'.$prodId.'@'.$sabor,
                        'ped_prodid'=>$prodId, 
                        'ped_prod'=>$NombreFin,
                        'ped_prodvar'=>$sabor,
                        'ped_prodpresenta'=>$producto->presentacion,
                        'ped_cant'=>$value,
                        'ped_costo'=>$producto->costo,
                        'ped_usrid'=>'0',
                        'ped_cantentregada'=>'0',
                        'ped_transfiere'=>'0',
                    ]);
                }else{
                    FoliosProdsModel::where('ped_act','1')
                        ->where('ped_folio',$FolioCoope)    
                        ->where('ped_producto',$comanda.':'.$prodId.'@'.$sabor )
                        ->update(['ped_cant'=>$value]);
                }
            }
            Alert::alert('Title', 'Message', 'Type');
            
        }
        
        return view('admon.ListasAbasto');
    }
}
