<?php

namespace App\Http\Controllers\admon;

use App\Models\FoliosModel;
use Illuminate\Http\Request;
use App\Models\FoliosProdsModel;
use Database\Seeders\FoliosSeeder;
use App\Http\Controllers\Controller;

class EntregaController extends Controller
{
    public function index(){
        session(['contador'=>'0']);
        return view('admon.Entrega');
    }

    public function store(request $request){
        
        $folio=$request['Folio'];
        #dd($folio,$request->all());
        foreach($request->all() as $key=>$value){
            if( preg_match("/folio$folio+/", $key)){
                ##### Obtiene el código de producto desde el $key
                $producto=preg_replace("/folio$folio\+/","",$key);
                
                ##### Obtiene datos previos del producto particularmente: ped_cantentregda
                $Previo=FoliosProdsModel::where('ped_act','1')
                    ->where('ped_folio',$folio)
                    ->where('ped_producto',$producto)
                    ->first();
                
                ##### Calcula si la entrega es parcial
                $fin=( $Previo->ped_cant - ($Previo->ped_cantentregada + $value) );
                if($fin=='0'){$entregado='1';}else{$entregado='0';}
                #dd($folio,$producto, $value, $Previo->ped_cantentregada, $Previo->ped_id, $fin, $entregado);

                ##### Actualiza la base dedatos
                FoliosProdsModel::where('ped_act','1')
                    ->where('ped_folio',$folio)
                    ->where('ped_producto',$producto)
                    ->update([
                        'ped_cantentregada'=> $Previo->ped_cantentregada + $value,
                        'ped_entregado'=>$entregado,
                    ]);
            }
        }
        ##### Revisa si se entregó todo el folio y marca el folio a estado fol_edo=1    
        $DatosDelFolio=FoliosProdsModel::where('ped_act','1')
            ->where('ped_folio',$folio)
            ->where('ped_entrega',session('ProximaCom')[0] )
            ->get();
            
        if($DatosDelFolio->count() == $DatosDelFolio->sum('ped_entregado')){
            FoliosModel::where('fol_id',$folio)->update([
                'fol_edo'=>'1',
            ]);
        }

        return redirect('/entrega');
    }
}
