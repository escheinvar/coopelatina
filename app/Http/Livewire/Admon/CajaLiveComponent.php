<?php

namespace App\Http\Livewire\Admon;

use Livewire\Component;
use App\Models\CajaModel;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class CajaLiveComponent extends Component
{
    public $petitcomite=['root','teso'];
    public $origen, $destino, $operacion, $monto,$concepto,$observa;
    public $hist_caja, $hist_tipo, $hist_mindate;
    
    public function mount(){
        $this->origen='caja';
        $this->destino='caja';
        $this->operacion='ingreso';

        $this->hist_caja='caja';
        $this->hist_tipo="%";
        $this->hist_mindate=date("Y-m-d");
    }


    public function cajaRegistra(){
        if($this->origen == $this->destino){
            $this->validate([
                'monto'=>'required',
                'concepto'=>'required',
            ]);

            if($this->operacion=='egreso'){$this->monto=$this->monto*-1;}
            CajaModel::create([
                'caja_'.$this->destino => $this->monto,
                'caja_responsable'=> auth()->user()->id,
                'caja_usrid'=>'0',
                'caja_tipo'=>$this->concepto,
                'caja_descripcion'=>'caja'.$this->operacion,
                'caja_observaciones'=>$this->observa,

            ]);
        }else{
            $this->validate([
                'monto'=>'required',
            ]);
            CajaModel::create([
                'caja_'.$this->origen => $this->monto * -1,
                'caja_'.$this->destino => $this->monto,
                'caja_responsable'=> auth()->user()->id,
                'caja_usrid'=>'0',
                'caja_tipo'=>'MovCajas',
                'caja_descripcion'=>'caja'.$this->operacion,
                'caja_observaciones'=>$this->observa,
            ]);
        }
        $this->emit('alerta','Registro en caja',"Se registró correctamente");
        $this->monto='';$this->concepto='';$this->operacion='ingreso';$this->concepto='';$this->observa='';
    }


    public function render(){ 
        $pag=7;
        #if($this->hist_caja=='caja'){
            $caja=CajaModel::where('caja_act','1')
                ->where('caja_tipo','LIKE', $this->hist_tipo)
                ->where('updated_at','LIKE',$this->hist_mindate.'%')
                ->where('caja_'.$this->hist_caja,'!=','0')
                ->orderBy('updated_at','desc')
                ->paginate($pag);   
        #}
        #dd($caja->all(), $this->hist_tipo, $this->hist_mindate, $this->hist_caja);
        $tiposCaja=CajaModel::distinct()->get('caja_tipo');

        #dd($caja->unique('caja_tipo'));
        return view('livewire.admon.caja-live-component',compact('caja'),['tiposCaja'=>$tiposCaja,]);
    }
}
