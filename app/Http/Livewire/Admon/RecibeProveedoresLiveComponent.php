<?php

namespace App\Http\Livewire\Admon;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RecibeProveedoresLiveComponent extends Component
{
    public $proxima, $proximaDate;
    public $petitCmte=['root','teso'];

    public function render(){   
        
        ##### Detecta próxima entrega
        #$ProxCom1=);
        #$ProxCom2=();
        if( session('ProxCom1')->start   >   session('ProxCom2')->start ){
            $this->proxima='com2';
            $this->proximaDate=session('ProxCom2');
        }else{
            $this->proxima='com1';
            $this->proximaDate=session('ProxCom1');
        }
        
        ##### Obtiene lista de productos de la entrega próxima
        ##### 1) ver tabla-folios activos, edo=pagado de mes y año 
        ##### 2) join de tabla-folios con tabla-foliosprods  para com1 o com2 y activos
        ##### 3) join  con tabla-productos para obtener proveedor de c/u
        $com=$this->proxima;
        $anio=$this->proximaDate['anio'];
        $mes=$this->proximaDate['mes'];
        $prods=DB::select(
            "SELECT proveedor, costo,  presentacion, SUM(ped_cant) AS total, CONCAT(ped_entrega,':',ped_prod,'@',ped_prodvar) AS prod FROM folios_prods 
            JOIN folios ON folios_prods.ped_folio=folios.fol_id
            JOIN productos ON folios_prods.ped_prodid=productos.id
            WHERE ped_act='1' AND ped_entrega= '$com' AND  fol_act='1' AND fol_edo='3' AND fol_anio='$anio' AND fol_mes= '$mes' 
            GROUP BY prod, proveedor, presentacion, costo");
            
        $prodsTien=DB::select(
            "SELECT proveedor, costo, SUM(ped_cant) AS total, CONCAT(ped_entrega,':',ped_prod,'@',ped_prodvar) AS prod FROM folios_prods 
            JOIN folios ON folios_prods.ped_folio=folios.fol_id
            JOIN productos ON folios_prods.ped_prodid=productos.id
            WHERE ped_act='1' AND ped_entrega= '$com' AND  fol_act='1' AND fol_edo='3' AND fol_anio='$anio' AND fol_mes= '$mes' AND fol_usrid = '0'
            GROUP BY prod, proveedor, costo");
            
        ##### Misma lista que anterior pero saca proovedores
        $proovs=DB::table('folios_prods')
            ->join('folios','folios_prods.ped_folio','=','folios.fol_id')
            ->join('productos','folios_prods.ped_prodid','=','productos.id')    
            ->where('ped_act','1')
            ->where('ped_entrega',$this->proxima)
            ->where('fol_act','1')
            ->where('fol_edo','3')
            ->where('fol_anio',$this->proximaDate['anio'])
            ->where('fol_mes',$this->proximaDate['mes'])
            ->distinct('proveedor')
            ->get();
        #dd('productos=',$prods,'productosTienda=', $prodsTien,'proveedores=', $proovs);
        
        return view('livewire.admon.recibe-proveedores-live-component',['productos'=>$prods,'productosTienda'=>$prodsTien, 'proveedores'=>$proovs]);
    }
}
