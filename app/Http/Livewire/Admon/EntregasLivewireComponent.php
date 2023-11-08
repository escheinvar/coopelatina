<?php

namespace App\Http\Livewire\Admon;

use App\Models\FoliosModel;
use App\Models\FoliosProdsModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class EntregasLivewireComponent extends Component
{
    
    public $BuscaNombre, $estado, $finalizado;

    public function mount(){
        $this->BuscaNombre="";
        $this->estado='3';
        $this->finalizado='0';  #fol_edo=3 ->pagado; fol_edo=2->entregado parcial fol_edo=1->Entregado total; fol_edo=0->Cancelado;
    }

    public function transfiere($prodId){
        ##### Obtiene datos del pedido a transferir
        $data=FoliosProdsModel::where('ped_id',$prodId)->first();        
        #dd($data);

        ##### Marca producto como transferido
        FoliosProdsModel::where('ped_id',$prodId)->update([
            'ped_transfiere'=>'1',
            'ped_entregado'=>'1'
        ]);

        ##### Genera nuevo folio
        $comanda=session('ProximaCom')[0];
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
        if($this->BuscaNombre==''){$Campo='%';}else{$Campo=$this->BuscaNombre;}
        ##### Carga datos de pedidos
        $pedidos=DB::table('folios_prods')
            ->join('folios','folios_prods.ped_folio','=','folios.fol_id')
            ->leftJoin('abastos', 'folios_prods.ped_producto','=','abastos.aba_producto')
            ->join('users', 'folios.fol_usrid','=','users.id')
            ->where(function ($q) {
                    if($this->BuscaNombre==''){$Campo='%';}else{$Campo=$this->BuscaNombre;}
                  return $q
                  ->where('nombre', 'ilike', '%'.$Campo.'%')
                  ->orWhere('ap_pat', 'ilike', '%'.$Campo.'%')
                  ->orWhere('ap_mat', 'ilike', '%'.$Campo.'%');
                })
            ->where('fol_act','1')
            ->where('fol_edo','=',$this->estado)
            ->where('fol_anio', session('ProximaCom')[1]['anio'])
            ->where('fol_mes', session('ProximaCom')[1]['mes'])
            ->where('fol_usrid','>','0')
            ->where('ped_act','1')
            ->where('ped_entrega',session('ProximaCom')[0])
            ->orderBy('nombre','asc')
            ->orderBy('ped_prodid','asc')
            ->orderBy('ped_id','asc')
            ->get();

        #### De la lsita de pedidos, obiente folios únicos
        $folios=$pedidos->unique('fol_id');
            
        #dd($pedidos->all());
        return view('livewire.admon.entregas-livewire-component',compact('folios'),['pedidos'=>$pedidos]);
    }
}
