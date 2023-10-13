<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Calendario;
use App\Models\FoliosModel;
use App\Models\FoliosProdsModel;
use App\Models\ProductosModel;

class Prepedidos01LivewireComponent extends Component
{
    public $prepedido, $anualidad, $com1=[], $com2=[], $ProxCom1, $ProxCom2;
    public $total, $GranTotal;
    public $oculta="inline-block";

    public function render(){
        #dd($this->prepedido);
        foreach($this->prepedido as $key=>$value){
            $entrega=preg_replace("/_.*/","",$key);
            $prodId=preg_replace("/@-.*/","",$key); 
            $prodId=preg_replace("/.*_/","",$prodId);
            
            ### Obtiene nombre
            $name1=ProductosModel::where('id',$prodId)->value('gpo');
            $name2=ProductosModel::where('id',$prodId)->value('nombre');
            $prodName=$name1." ".$name2;
            
            ### Obtiene Precio
            if(auth()->user()->estatus  == 'act'){
                $precio=ProductosModel::where('id',$prodId)->value('precioact');
            }else if(auth()->user()->estatus  == 'reg') {
                $precio=ProductosModel::where('id',$prodId)->value('precioreg');
            }else if(auth()->user()->estatus  == 'pru') {
                $precio=ProductosModel::where('id',$prodId)->value('precioreg');
            }else{
                $precio=ProductosModel::where('id',$prodId)->value('preciopub');
            }
            

            $presenta=ProductosModel::where('id',$prodId)->value('presentacion');
            $sabor=preg_replace("/.*@-/","",$key);
            
            if($entrega == 'com1'){
                $this->com1[$key]=['com'=>$entrega, 'prodId'=>$prodId, 'prodName'=>$prodName, 'presenta'=>$presenta, 'sabor'=>$sabor,'cant'=>$value, 'precio'=>$precio];
            }else{
                $this->com2[$key]=['com'=>$entrega, 'prodId'=>$prodId, 'prodName'=>$prodName, 'presenta'=>$presenta, 'sabor'=>$sabor,'cant'=>$value, 'precio'=>$precio];
            }
        }        
        return view('livewire.prepedidos01-livewire-component');
    }
    
    #############################################################################################################
    ############################################################################################## Guarda datos
    public function CrearPrePedido(){
        #dd(session());

        $this->oculta="none";

        if($this->anualidad > 0){$edo='5';}else{$edo='4';}
        ##### Genera FOLIO
        $folio=FoliosModel::create([
            'fol_act'=>'1',
            'fol_edo'=>$edo,
            'fol_anio'=> preg_replace("/-.*/","", session('ProxCom1')['end']),
            'fol_mes'=> session('ProxCom1')['mes'],
            'fol_usrid'=>auth()->user()->id,
            'fol_usr'=>auth()->user()->usr,
        ]);
        ##### Guarda Anualidad
        if($this->anualidad > 0){
            FoliosProdsModel::create([
                'ped_act'=>'1',
                'ped_folio'=>$folio->fol_id,
                'ped_entregado'=>'0',
                'ped_entrega'=>'com1',
                'ped_prodid'=>'0',
                'ped_prod'=>'Anualidad',
                'ped_prodvar'=>'anualidad',
                'ped_prodpresenta'=>'pago anual',
                'ped_costo' => $this->anualidad,
                'ped_cant'=>'1',
                'ped_usrid'=>auth()->user()->id,
                'ped_cantentregada'=>'0',
                'ped_transfiere'=>'0',
            ]);
        }
        ##### GuardaProductos        
        
        foreach($this->prepedido as $key=>$value){
            $entrega=preg_replace("/_.*/","",$key);
            $prodId=preg_replace("/.*_/","",$key); $prodId=preg_replace("/@-.*/","",$prodId);
            $sabor=preg_replace("/.*@-/","",$key);

            ### Obtiene nombre
            $name1=ProductosModel::where('id',$prodId)->value('gpo');
            $name2=ProductosModel::where('id',$prodId)->value('nombre');
            $presenta=ProductosModel::where('id',$prodId)->value('presentacion');

            ### Obtiene Precio
            if(auth()->user()->estatus  == 'act'){        $precio=ProductosModel::where('id',$prodId)->value('precioact');
            }else if(auth()->user()->estatus  == 'reg') {  $precio=ProductosModel::where('id',$prodId)->value('precioreg');
            }else if(auth()->user()->estatus  == 'pru') {  $precio=ProductosModel::where('id',$prodId)->value('precioreg');
            }else{                $precio=ProductosModel::where('id',$prodId)->value('preciopub'); }

            FoliosProdsModel::create([
                'ped_act'=>'1',
                'ped_folio'=>$folio->fol_id,
                'ped_entregado'=>'0',
                'ped_entrega'=>$entrega,
                'ped_prodid'=>$prodId,
                'ped_prod'=>$name1." ".$name2,
                'ped_prodvar'=>$sabor,
                'ped_prodpresenta'=>$presenta,
                'ped_costo' => $precio,
                'ped_cant'=>$value,
                'ped_usrid'=>auth()->user()->id,
                'ped_cantentregada'=>'0',
                'ped_transfiere'=>'0',
            ]);
        }

        $this->emit('alerta','Pre pedido','Guardado exitosamente!<br>No olvides pagarlo');
        
        return redirect("/MisPedidos/".auth()->user()->usr );
    }

}
