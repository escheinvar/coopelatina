<?php

namespace App\Http\Livewire;

use DateTime;
use App\Models\User;
use Livewire\Component;
use App\Models\TrabajosModel;
use Illuminate\Support\Facades\DB;

class TrabajosLivewireComponent extends Component
{
    public $clubToby=['root','teso','admon'];
    public $usuarioBusca, $anioBusca='0', $mesBusca='0',$inscripcion='', $antiguedad='',$usrdata, $anualidad='', $a, $y, $m,$d;
    public $busca='', $listaUsrs, $salidaBusca, $seleccionado, $FechaTrabajo, $DescripTrabajo;
    

    public function updatedBusca(){
        if($this->busca !=''){
            $this->listaUsrs=User::where('nombre', 'ilike',  '%'.$this->busca.'%')
                ->orWhere('ap_pat', 'ilike',  '%'.$this->busca.'%')
                ->orWhere('ap_mat', 'ilike',  '%'.$this->busca.'%')
                ->take(15)
                ->get();
        }else{
            $this->listaUsrs=User::get();
        }
        
    }

    public function RegistraTrabajo(){
        $this->validate([
            'FechaTrabajo'=>'required',
            'DescripTrabajo'=>'required',
        ]);

        $nombre=User::where('id',$this->usuarioBusca)->first();
        $anio=preg_replace("/-.*/","",$this->FechaTrabajo);
        $mes=preg_replace("/....-/","",$this->FechaTrabajo);
        $mes=preg_replace("/-../","",$mes);

        TrabajosModel::insert([
            'work_act'=>'1',
            'work_usrid'=>$this->usuarioBusca,
            'work_usr'=>$nombre->nombre." ".$nombre->ap_pat." ".$nombre->ap_mat,
            'work_responsableid'=>auth()->user()->id,
            'work_mes'=>$mes,
            'work_anio'=>$anio,
            'work_fechatrabajo'=>$this->FechaTrabajo,
            'work_descripcion'=>$this->DescripTrabajo,

        ]);
         $this->emit('alerta','Trabajo Registrado',"Se registró correctamente el trabajo de ".$nombre->nombre);
    }

    public function mount(){
        $this->usuarioBusca=auth()->user()->id;
        $this->listaUsrs=User::get();
        $this->busca=User::where('id',auth()->user()->id)->value('nombre');
    }

    public function render(){   
        if($this->seleccionado){  ###en modal, muestra el cooperativista
            $this->salidaBusca=User::where('id',$this->seleccionado)->first();
        }
        if($this->usuarioBusca =='0'){$bla1='>';}else{$bla1='=';}
        if($this->anioBusca =='0'){$bla2='>';}else{$bla2='=';}
        if($this->mesBusca =='0'){$bla3='>';}else{$bla3='=';}
        
        $trabajos=DB::table('trabajos')
            ->join('users','trabajos.work_usrid','=','users.id')
            ->where('work_act','1')
            ->where('work_usrid',$bla1,$this->usuarioBusca)
            ->where('work_anio',$bla2,$this->anioBusca)
            ->where('work_mes',$bla3,$this->mesBusca)
            ->orderBy('work_fechatrabajo','desc')
            ->get(); 

        if($this->usuarioBusca >'0'){
            $this->inscripcion=User::where('id',$this->usuarioBusca)->value('dateregistro');
            $this->anualidad=User::where('id',$this->usuarioBusca)->value('membrefin');
            $a1 = new DateTime($this->inscripcion);    
            $b1 = new DateTime($this->anualidad);  
            $antiguedad= $b1->diff($a1);
            $this->a=$antiguedad->y;
            $this->m=$antiguedad->m;
            $this->d=$antiguedad->d;

           $this->usrdata=User::where('id',$this->usuarioBusca)->first();
           #dd($this->usrdata);
        }

        $personas=User::orderBy('nombre','asc')->get();
        return view('livewire.trabajos-livewire-component',[
            'trabajos'=>$trabajos,
            'coops'=>$personas,
            'usr'=>['inscripcion'=>$this->inscripcion,'anualidad'=>$this->anualidad, 'antAnio'=>$this->a, 'antMes'=>$this->m, 'antDia'=>$this->d, 'usrdata'=>$this->usrdata], 
        ]);
    }
}
