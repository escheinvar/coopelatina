<?php

namespace App\Http\Livewire;

use DateTime;
use App\Models\User;
use Livewire\Component;
use App\Models\Calendario;
use Illuminate\Http\Request;
use App\Models\TrabajosModel;
use Livewire\WithFileUploads;
use App\Models\ProductosModel;
use App\Models\ProductoresModel;
use Illuminate\Support\Facades\DB;

class PrepedidosLivewireComponent extends Component
{
    public $VerAnual='block', $NoVerAnual='none';
    public $trabajos=[];
    public $text1, $datos;
    public $prodid, $activo, $gpo, $nombre, $variantes, $presentacion, $entrega, $venta, $costo, $precioact, $precioreg;
    public $preciopub, $mintipo, $min, $proveedor, $categoria, $responsable, $descripcion, $img, $orden;
    public $Grupos, $gpo2, $productores, $responsables, $categorias, $SubeImagen;
    use WithFileUploads;

    public function PagarAnualidad(){
        $this->VerAnual='none';
        $this->NoVerAnual='block';
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
        ProductosModel::updateOrCreate(['id'=>$prodid],[
            'activo'=>$this->activo,
            'gpo'=>$this->gpo,
            'nombre'=>$this->nombre,
            'variantes'=>$this->variantes,
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
        if($this->activo =='0'){$this->activo='';}

        $this->Grupos=ProductosModel::distinct('gpo')->get('gpo');
        $this->productores=ProductoresModel::where('prod_act','1')->distinct('prod_nombrecorto')->get('prod_nombrecorto');  
        $this->categorias=ProductosModel::distinct('categoria')->get('categoria'); 
        $this->responsables=User::where('activo','1')->where('estatus','act')->get();
        
        ##### Obtiene lista de productosproveedorproveedor
        $todo = ProductosModel::where('activo','1')
            ->where('entrega','not like','no')
            ->orderBy('gpo','asc')
            ->get();

        $Inacts = ProductosModel::where('activo','0')
            ->orWhere('entrega','no')
            ->orderBy('gpo','asc')
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
        return view('livewire.prepedidos-livewire-component',compact('todo'),['YaPedido'=>$YaPedidos,'inacts'=>$Inacts]);
    }
}
