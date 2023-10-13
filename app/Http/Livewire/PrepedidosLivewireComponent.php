<?php

namespace App\Http\Livewire;

use DateTime;
use Livewire\Component;
use App\Models\Calendario;
use App\Models\TrabajosModel;
use App\Models\ProductosModel;

class PrepedidosLivewireComponent extends Component
{
    public $VerAnual='block', $NoVerAnual='none';
    public $trabajos=[];

    public function PagarAnualidad(){
        $this->VerAnual='none';
        $this->NoVerAnual='block';
    }

    
    public function render(){
        ##### Obtiene lista de productos
        $todo = ProductosModel::where('activo','1')
            ->where('entrega','not like','no')
            ->orderBy('gpo')
            ->get();

        
        return view('livewire.prepedidos-livewire-component',compact('todo'));
    }
}
