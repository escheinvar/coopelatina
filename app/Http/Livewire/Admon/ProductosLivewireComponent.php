<?php

namespace App\Http\Livewire\Admon;

use App\Models\User;
use Livewire\Component;
use App\Models\EstadosModel;
use App\Models\ProductosModel;
use App\Models\ProductoresModel;
use Illuminate\Support\Facades\Request;


class ProductosLivewireComponent extends Component
{
    public $Grupos, $productores,$categorias,$responsables;
    public $ordenTabla, $sentidoTabla;
    public $EnOcasion, $text1, $prodid, $activo;
    public $gpo, $gpo2, $nombre, $variantes, $presentacion, $entrega, $venta, $costo, $precioact, $precioreg;
    public $preciopub, $mintipo, $min, $proveedor, $categoria, $responsable, $descripcion, $img;
    public $orden, $SubeImagen;

    public function mount(){
        $this->ordenTabla="id";
        $this->sentidoTabla="asc";
        if(session('ocasion')=='1'){$this->EnOcasion='1';}
    }

    public function reordenaTabla($x){
        if($this->sentidoTabla=='asc'){
            $this->sentidoTabla='desc';
        }else{
            $this->sentidoTabla='asc';
        }
        $this->orden=$x;
    }

    public function CambiaEstadoOcasion(){
        if(session('ocasion')=='1' ){
            $this->EnOcasion='';
            session(['ocasion'=>'']);
            EstadosModel::where('edo_name','Ocasion')->update(['edo_edo'=>'']);
        }else{
            $this->EnOcasion='1';
            session(['ocasion'=>'1']);
            EstadosModel::where('edo_name','Ocasion')->update(['edo_edo'=>'1']);
        }
    }

    public function AbrirModal($tipo,$idprod){
        if($tipo=='edit'){
            $this->text1='Editar';
            $datos=ProductosModel::where('id',$idprod)->first();
            $this->prodid=$datos->id;
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
            $this->mintipo=$datos->mintipo;
            $this->min=$datos->min;
            $this->proveedor=$datos->proveedor;
            $this->categoria=$datos->categoria;
            $this->responsable=$datos->responsable;
            $this->descripcion=$datos->descripcion;
            $this->img=$datos->img;
            $this->orden=$datos->orden;
            $this->SubeImagen='';

        }elseif($tipo=='nvo'){
            $this->text1='Nuevo';
            $this->prodid="";
            
            $this->activo ="";$this->gpo="";$this->nombre="";$this->variantes="";$this->presentacion="";$this->entrega="";
            $this->venta="";$this->costo="";$this->precioact="";$this->precioreg="";$this->preciopub="";$this->mintipo="";
            $this->proveedor="";$this->categoria="";$this->responsable="";$this->descripcion="";$this->img="";$this->SubeImagen=""; $this->orden="";
        }
        
    }

    public function BorrrarImg($prodid){
        ProductosModel::where('id',$prodid)->update(['img'=>null]);
        $this->emit('alerta','Imagen Borrada',"La imágen del producto $prodid fué borrada exitosamente!!");
    }

    public function GuardaEdita($prodid, Request $request){
        
        ##### Valida info
        if($this->gpo=='NUEVO'){$this->gpo=$this->gpo2;}
        if($this->activo==''){$this->activo='0';}
        
        $this->validate([
            'gpo'=>'required',
            'nombre'=>'required',
            'presentacion'=>'required',
            'entrega'=>'required',
            'venta'=>'required',
            'costo'=>'required',
            'precioact'=>'required',
            'precioreg'=>'required',
            'preciopub'=>'required',
            'mintipo'=>'required',
            'proveedor'=>'required',
            'categoria'=>'required',
            'responsable'=>'required',
            'descripcion'=>'required',
        ]);

        if($this->SubeImagen){
            ##### Genera nombre
            $ArchName='prodId'.$prodid."_".preg_replace("/ /","",$this->activo)."_".preg_replace("/ /","",$this->nombre);         
            $rutaStorage="public/productos/";
            $rutaPublica="/storage/productos/";
            ##### Sube imagen
            $this->SubeImagen->storeAs($rutaStorage, $ArchName);
            $this->img = $rutaPublica.$ArchName;
        }

        ##### Actualiza base de datos
        $variantes=str_replace("/ /","_",$this->variantes);
        ProductosModel::updateOrCreate(['id'=>$prodid],[
            'activo'=>$this->activo,
            'gpo'=>$this->gpo,
            'nombre'=>$this->nombre,
            'variantes'=>$variantes,
            'presentacion'=>$this->presentacion,
            'entrega'=>$this->entrega,
            'venta'=>$this->venta,
            'costo'=>$this->costo,
            'precioact'=>$this->precioact,
            'precioreg'=>$this->precioreg,
            'preciopub'=>$this->preciopub,
            'mintipo'=>$this->mintipo,
            'min'=>$this->min,
            'proveedor'=>$this->proveedor,
            'categoria'=>$this->categoria,
            'responsable'=>$this->responsable,
            'descripcion'=>$this->descripcion,
            'img'=>$this->img,
            'orden'=>'9999',
        ]);
        $this->emit('alerta','Cambios aplicados',"Se registra $this->nombre fué editado correctamente!!");
       
    }

    
    public function render(){
        $productos=ProductosModel::orderBy($this->ordenTabla,$this->sentidoTabla)->get();
        $ocasion=ProductosModel::where('activo','1')->where('entrega','oca')->get();
        $this->Grupos=ProductosModel::distinct('gpo')->get('gpo');
        $this->productores=ProductoresModel::where('prod_act','1')->distinct('prod_nombrecorto')->get('prod_nombrecorto');  
        $this->categorias=ProductosModel::distinct('categoria')->get('categoria'); 
        $this->responsables=User::where('activo','1')->where('estatus','act')->get();

        return view('livewire.admon.productos-livewire-component', compact('productos'), [
            'ocasion'=>$ocasion,
        ]);
    }
}
