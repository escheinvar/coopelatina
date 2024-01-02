<?php

namespace App\Http\Livewire;

use App\Models\EstadosModel;
use Livewire\Component;

class OcasionLivewireComponent extends Component
{
    public $EnOcasion;

    public function CambiaEstado(){
        if(session('ocasion')=='1'){
            $this->EnOcasion='';
            session(['ocasion'=>'']);
            EstadosModel::where('edo_name','Ocasion')->update(['edo_edo'=>'']);
        }else{
            $this->EnOcasion='1';
            session(['ocasion'=>'1']);
            EstadosModel::where('edo_name','Ocasion')->update(['edo_edo'=>'1']);
        }
    }

    public function render(){
        if(session('ocasion')=='1'){$this->EnOcasion='1';}
        return view('livewire.ocasion-livewire-component');
    }
}
