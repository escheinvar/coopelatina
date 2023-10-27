<?php

namespace App\Http\Livewire\Admon;

use Livewire\Component;
use App\Models\FoliosModel;
use App\Models\ProductosModel;
use Illuminate\Support\Facades\DB;

class ListasAbastoLiveComponent extends Component
{
    public $SelEncar='%', $SelProd="%";
    public $orden='responsable', $sentido='asc';
    #public $ValorPed1;
    
    public function orden($campo){  
        $this->orden = $campo;
        if($this->sentido == 'asc'){
            $this->sentido = 'desc';
        }else{
            $this->sentido = 'asc';
        }        
    }

    public function render() {
        $prods=ProductosModel::where('activo','1')  
            ->selectRaw("*,CONCAT(gpo,' ',nombre) AS prodvar")
            ->where('responsable','like',$this->SelEncar)
            ->where('proveedor','like',$this->SelProd)
            ->orderBy($this->orden,$this->sentido)
            ->get();

        $encargados=ProductosModel::select('responsable')->where('activo','1')->distinct()->orderBy('responsable','asc')->get();
        $productores=ProductosModel::select('proveedor')->where('activo','1')->distinct()->orderBy('proveedor','asc')->get();

        ######### GENERA LISTA DE TOTAL DE PRODUCTOS POR TIPO DEL MES DE USUARIOS (NO TIENDA): genera tabla con dos campos:
        ######### "total": Suma de número de productos
        ######### "prod": Producto definido como:   ped_entrega:ped_prod@ped_prodvar       x   ej: com1:Amaranto Obleas@Coco
        #########                                        Ojo:   ped_prod=gpo+" "+nombre    y   ped_prodvar = 1 sabor(variante)
        ######### Excluye pedidos y folios inactivos, productos ya entregados, folios en estado distinto a 3 y pedidos de tienda
        $mes=session('ProxCom2date')['mes']; 
        $anio=session('ProxCom2date')['anio']; 
        $pedidos=DB::select(
            "SELECT SUM(ped_cant) AS total, CONCAT(ped_entrega,':',ped_prodid,'@',ped_prodvar) AS prod FROM folios_prods 
            JOIN folios ON folios_prods.ped_folio=folios.fol_id
            WHERE ped_act='1' AND  fol_act='1' AND fol_edo='3' AND fol_anio=$anio AND fol_mes=$mes  AND fol_usrid > '0'
            GROUP BY prod");

        ######### GENERA LISTA DE TOTAL DE PRODUCTOS POR TIPO DEL MES PERO SOLO DE TIENDA: (ajustar a folio de tienda del mes!!!! )
        $tienda=DB::select(
            "SELECT SUM(ped_cant) AS total,CONCAT(ped_entrega,':',ped_prodid,'@',ped_prodvar) AS prod FROM folios_prods 
            JOIN folios ON folios_prods.ped_folio=folios.fol_id
            WHERE ped_act='1' AND fol_act='1' AND fol_anio=$anio AND fol_mes=$mes  AND fol_usrid = '0'
            GROUP BY prod");

        return view('livewire.admon.listas-abasto-live-component',['prods'=>$prods, 'pedidos'=>$pedidos, 'tienda'=>$tienda, 'encargados'=>$encargados,'productores'=>$productores]);
    }
}
