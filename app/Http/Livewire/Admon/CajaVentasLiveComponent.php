<?php

namespace App\Http\Livewire\Admon;

use App\Models\User;
use Livewire\Component;
use App\Models\FoliosModel;
use App\Models\EnvasesModel;
use App\Models\ProductosModel;
use App\Models\FoliosProdsModel;
use Illuminate\Support\Facades\DB;

class CajaVentasLiveComponent extends Component
{
    public $busca, $idusr='21', $usr, $usuarios, $envases, $pedidos;
  

    public function transfiere($prodId){  /* --ojo: esta función existe también en EntregasLivewireComponent */
        ##### Obtiene datos del pedido a transferir
        $data=FoliosProdsModel::where('ped_id',$prodId)->first();        
        #dd($data);

        ##### Marca producto como transferido
        FoliosProdsModel::where('ped_id',$prodId)->update([
            'ped_transfiere'=>'1',
            'ped_entregado'=>'1'
        ]);

        ##### Genera nuevo folio
        #$comanda=session('ProximaCom')[0];
        $mes=session('ProximaCom')[1]['mes'];
        $anio=session('ProximaCom')[1]['anio'];
        if($mes=='12'){
            $mes='1';
            $anio=$anio+1;
        }else{
            $mes=$mes+1;
        }
        $folio=FoliosModel::firstOrCreate(['fol_act'=>'1',  'fol_edo'=>'3', 'fol_anio'=>$anio, 'fol_mes'=>$mes, 'fol_usrid'=>$data->ped_usrid], 
        [
            'fol_act'=>'1',
            'fol_edo'=>'3',
            'fol_anio'=> $anio,
            'fol_mes'=> $mes,
            'fol_usrid'=>$data->ped_usrid,
            'fol_usr'=>'Traspaso',
        ]);

        ##### Genera nuevo pedido
        FoliosProdsModel::create([
            'ped_act'=>'1',
            'ped_folio'=>$folio->fol_id,
            'ped_entregado'=>'0',
            'ped_producto'=>$data->ped_producto,
            'ped_entrega'=>$data->ped_entrega,
            'ped_prodid'=>$data->ped_prodid,
            'ped_prod'=>$data->ped_prod,
            'ped_prodvar'=>$data->ped_prodvar,
            'ped_prodpresenta'=>$data->ped_prodpresenta,
            'ped_costo' => $data->ped_costo,
            'ped_cant'=>$data->ped_cant,
            'ped_usrid'=>$data->ped_usrid,
            'ped_cantentregada'=>'0',
            'ped_transfiere'=>'0',
        ]);

        #### Verifica si el pedido fue entregado totalmente:
        $DatosDelFolio=FoliosProdsModel::where('ped_act','1')
            ->where('ped_folio',$data->ped_folio)
            ->where('ped_entrega',$data->ped_entrega)
            ->get();
        if($DatosDelFolio->count() == $DatosDelFolio->sum('ped_entregado')){
            FoliosModel::where('fol_id',$data->ped_folio)->update([
                'fol_edo'=>'1',
            ]);
        }
        #dd($data->ped_folio,$DatosDelFolio,$DatosDelFolio->count(), $DatosDelFolio->sum('ped_entregado'));        
    }

    public function render(){
        ##### Obtiene listado de usuarios para select
        $this->usuarios=DB::select("SELECT * from users 
            WHERE activo='1' AND CONCAT(nombre,' ',ap_pat,' ',ap_mat) ILIKE '%".$this->busca."%'");

        ##### Obtiene datos del usuario seleccionado (si es que fue seleccionado)
        if($this->idusr > '0'){
            $this->usr=User::where('id',$this->idusr)->first();
        }
        ##### Obtiene tabla de productos para venta
        $productos=ProductosModel::where('activo','1')
            ->where('venta','si')
            ->orWhere('entrega','no')
            ->get();
        
        ##### Obtiene tabla de envases
        $this->envases=EnvasesModel::where('fco_act','1')->get();

        ##### Obtiene tabla de pedidos pendientes del usuario
        if($this->idusr > '0'){
            $com=session('ProximaCom')[0];
            $anio=session('ProximaCom')[1]['anio'];
            $mes=session('ProximaCom')[1]['mes'];
            $idUsr=$this->usr->id;

            $this->pedidos=DB::select(
                "SELECT *  FROM folios_prods 
                JOIN folios ON folios_prods.ped_folio=folios.fol_id
                JOIN productos ON folios_prods.ped_prodid=productos.id
                join abastos on folios_prods.ped_producto = abastos.aba_producto 
                WHERE ped_act='1' AND ped_entregado='0' AND ( ped_cant > ped_cantentregada)
                AND  fol_act='1' AND fol_edo='3' AND fol_anio='$anio' AND fol_mes= '$mes' AND fol_usrid = '$idUsr'
                AND (aba_abasto ='0'    or aba_faltante ='1') ");
            #dd($anio,$mes,$com,$idUsr,$this->pedidos);
        }

        return view('livewire.admon.caja-ventas-live-component',compact('productos'));
    }
}
