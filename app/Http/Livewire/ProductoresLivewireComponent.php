<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use App\Models\ProductoresModel;
use App\Models\ProductosModel;
use Illuminate\Support\Facades\DB;



class ProductoresLivewireComponent extends Component
{
    public $clubToby=['root','teso','admon'];
    public $text1, $prodid, $activo, $nombrecorto, $nombrelargo, $contacto, $tel, $correo, $descripcion;
    public $direccion, $http, $facebook, $instagram, $youtube, $tipo, $logo, $orden, $SubeImagen, $SubeFoto;
    public $CarruselDeFoto;
    use WithFileUploads;
    

    public function AbrirModal($tipo,$idprod){
        if($tipo=='edit'){
            $this->text1='Editar';
            $datos=ProductoresModel::where('prod_id',$idprod)->first();
            $this->SubeImagen='';
            $this->prodid=$datos->prod_id;
            $this->activo=$datos->prod_act;
            if($this->activo=='0'){$this->activo='';}
            $this->nombrecorto=$datos->prod_nombrecorto;
            $this->nombrelargo=$datos->prod_nombrelargo;
            $this->contacto=$datos->prod_contacto;
            $this->tel=$datos->prod_tel;
            $this->correo=$datos->prod_correo;
            $this->descripcion=$datos->prod_descripcion;
            $this->direccion=$datos->prod_direccion;
            $this->http=$datos->prod_http;
            $this->facebook=$datos->prod_facebook;
            $this->instagram=$datos->prod_instagram;
            $this->youtube=$datos->prod_youtube;
            $this->tipo=$datos->prod_tipo;
            $this->logo=$datos->prod_logo;
            $this->orden=$datos->prod_orden;
        }elseif($tipo=='nvo'){
            $this->text1='Nuevo';
            $this->prodid='';$activo='1';$this->nombrecorto=$this->nombrelargo=$this->contacto=$this->tel=$this->correo=$this->descripcion='';
            $this->direccion=$this->http=$this->facebook=$this->instagram=$this->youtube=$this->tipo=$this->logo=$this->orden=$this->SubeImagen='';
        }
    }

    public function BorrrarImg($prodid){
        ProductoresModel::where('prod_id',$prodid)->update(['prod_logo'=>null]);
        $this->emit('alerta','Imagen Borrada',"La imágen del producto $prodid fué borrada exitosamente!!");
    }

    public function GuardaEdita($prodid, Request $request){
        ##### Valida info
        if($this->activo==''){$this->activo='0';}
        $this->validate([
            'nombrecorto'=>'required',
            'nombrelargo'=>'required',
        ]);

        if($this->SubeImagen){
            ##### Genera nombre
            $ArchName=$this->prodid."_".preg_replace("/ /","",$this->nombrecorto)."_Logo";
            $rutaStorage="public/productores/";
            $rutaPublica="/storage/productores/";
            ##### Sube imagen
            $this->SubeImagen->storeAs($rutaStorage, $ArchName);
            $this->logo = $rutaPublica.$ArchName;
        }

        ##### Actualiza base de datos
        ProductoresModel::updateOrCreate(['prod_id'=>$prodid],[
            'prod_act'=>$this->activo,
            'prod_nombrecorto'=>$this->nombrecorto,
            'prod_nombrelargo'=>$this->nombrelargo,
            'prod_contacto'=>$this->contacto,
            'prod_tel'=>$this->tel,
            'prod_correo'=>$this->correo,
            'prod_descripcion'=>$this->descripcion,
            'prod_http'=>$this->http,
            'prod_facebook'=>$this->facebook,
            'prod_instagram'=>$this->instagram,
            'prod_'=>$this->youtube,
            'prod_youtube'=>$this->tipo,
            'prod_logo'=>$this->logo,
            'prod_orden'=>$this->orden,
        ]);
        $this->emit('alerta','Cambios aplicados',"Se guardaron cambios a $this->nombrecorto !!");
       
    }

    public function BorrrarImgDeArray($prodId,$Imagen){
        $img=[$Imagen,];
        $Fotos=ProductoresModel::where('prod_id',$prodId)->first()->toArray('prod_fotos');#pluck('prod_fotos');
        $arreglo=explode(";",$Fotos['prod_fotos']);   
        $nuevo=implode(";",array_diff($arreglo,[$Imagen]));
        ProductoresModel::where('prod_id',$prodId)->update(['prod_fotos'=>$nuevo]);
        $this->emit('alerta','Cambios aplicados',"Se eliminó la fotografía del carrusel !!");
    }

    public function SubirImgDeArray($prodId){
        ##### Genera nombre
        
        $NombreCorto=ProductoresModel::where('prod_id',$prodId)->value('prod_nombrecorto');
        $ArchName=$prodId."_".preg_replace("/ /","",$NombreCorto)."_FOTO".date("ymd_His");#.".".getClientOriginalExtension($this->SubeFoto);
        $rutaStorage="public/productores/";
        $rutaPublica="/storage/productores/";
        
        ##### Sube imagen
        $this->SubeFoto->storeAs($rutaStorage, $ArchName);
        $NvaFoto=$rutaPublica.$ArchName;

        ##### Modifica base
        $Fotos=ProductoresModel::where('prod_id',$prodId)->value('prod_fotos');
        if(is_null($Fotos) OR $Fotos == ''){
            $nuevo=$NvaFoto;
        }else{
            $Fotos=explode(";",$Fotos); 
            $Fotos[]=$NvaFoto;
            $nuevo=implode(";",$Fotos);        
        }
        ProductoresModel::where('prod_id',$prodId)->update(['prod_fotos'=>$nuevo]);   
        $this->SubeFoto='';
        $this->emit('alerta','Cambios aplicados',"Se agregó una fotogografía al carrusel !!");
    }

    public function render(){
        $prods=ProductoresModel::where('prod_act','1')->get();

        $prodsInact=ProductoresModel::where('prod_act','0')->get();
        
        return view('livewire.productores-livewire-component',compact('prods'),['prodsInact'=>$prodsInact]);
    }
}
