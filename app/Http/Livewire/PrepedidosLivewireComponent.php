<?php

namespace App\Http\Livewire;

use DateTime;
use Livewire\Component;
use App\Models\Calendario;
use App\Models\TrabajosModel;
use App\Models\ProductosModel;
use Illuminate\Support\Facades\DB;

class PrepedidosLivewireComponent extends Component
{
    public $VerAnual='block', $NoVerAnual='none';
    public $trabajos=[];
    public $prod_id,$text1, $datos;
    public $activo, $gpo, $nombre, $variantes, $presentacion, $entrega, $venta, $costo, $precioact, $precioreg;
    public $preciopub, $mintipo, $proveedor, $categoria, $responsable, $descripcion, $img, $orden;

    public function PagarAnualidad(){
        $this->VerAnual='none';
        $this->NoVerAnual='block';
    }

    public function AbrirModal($tipo,$idprod){
        if($tipo=='edit'){
            $this->text1='Editar';
            $datos=ProductosModel::where('id',$idprod)->first();

            $this->activo =$datos->activo;
            $this->gpo=$datos->gpo;
            $this->nombre=$datos->nombre;
            $this->variantes=$datos->variantes;
            $this->presentacion=$datos->presentacion;
            $this->entrega=$datos->entrega;
            $this->venta=$datos->venta;
            $this->costo=$datos->costo;
            $this->precioact=$datos->precioact;
            $this->precioreg=$datos->precioreg;
            $this->preciopub=$datos->preciopub;
            $this->mintipo=$datos->min;
            $this->proveedor=$datos->proveedor;
            $this->categoria=$datos->categoria;
            $this->responsable=$datos->responsable;
            $this->descripcion=$datos->descripcion;
            $this->img=$datos->img;
            $this->orden=$datos->orden;

        }elseif($tipo=='nvo'){
            $this->text1='Nuevo';
            
            $this->activo ="";$this->gpo="";$this->nombre="";$this->variantes="";$this->presentacion="";$this->entrega="";
            $this->venta="";$this->costo="";$this->precioact="";$this->precioreg="";$this->preciopub="";$this->mintipo="";
            $this->proveedor="";$this->categoria="";$this->responsable="";$this->descripcion="";$this->img="";$this->orden="";
        }
        $this->prod_id=$idprod;
    }

    public function render(){
        ##### Obtiene lista de productos
        $todo = ProductosModel::where('activo','1')
            ->where('entrega','not like','no')
            ->orderBy('gpo')
            ->get();
        
        ######### GENERA LISTA DE TOTAL DE PRODUCTOS POR TIPO DEL MES DE USUARIOS (NO TIENDA): genera tabla con dos campos:
        ######### "total": Suma de número de productos
        ######### "prod": Producto definido como:   ped_entrega:ped_prod@ped_prodvar       x   ej: com1:Amaranto Obleas@Coco
        #########                                        Ojo:   ped_prod=gpo+" "+nombre    y   ped_prodvar = 1 sabor(variante)
        ######### Excluye pedidos y folios inactivos, productos ya entregados, folios en estado distinto a 3 y pedidos de tienda
        $mes=session('ProxPedido')['mes']; 
        $anio=session('ProxPedido')['anio']; 

        
        $YaPedidos=DB::select(
            "SELECT SUM(ped_cant) AS total, ped_prodid FROM folios_prods 
            JOIN folios ON folios_prods.ped_folio=folios.fol_id
            WHERE ped_act='1' AND  fol_act='1' AND fol_edo>='3' AND fol_anio=$anio AND fol_mes=$mes
            GROUP BY ped_prodid");
        #dd($YaPedidos);
        return view('livewire.prepedidos-livewire-component',compact('todo'),['YaPedido'=>$YaPedidos]);
    }
}
