<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\FoliosModel;
use Livewire\WithFileUploads;
use App\Models\FoliosProdsModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class MispedidosLivewireComponent extends Component
{
    #public $GranVariable="activos";  
    public $GranVariable="activos";
    public $folios, $prods;

    use WithFileUploads;
    public $SubeComprobPago, $SubeComprTipo;
    


    public function SubirPago(Request $request){
        ##### Valida campos
        $this->validate([
            'SubeComprTipo'=>'required',
            'SubeComprobPago'=>'file|mimes:png,jpg,pdf|max:10240',
        ]);
        

        ##### Genera nombre
        $ArchMes=preg_replace("/.*_/","", $this->SubeComprTipo);
        $ArchFolio=preg_replace("/_.*/","", $this->SubeComprTipo);
        $nombre="ComproPago".sprintf('%04d', $ArchFolio);
        $rutaStorage="public/".$ArchMes;
        $rutaPublica="/storage/".$ArchMes;
        
        ##### Guarda archivo
        $this->SubeComprobPago->storeAs($rutaStorage, $nombre);

        ##### Guarda en Base de datos
        FoliosModel::where('fol_id',$ArchFolio)
            ->update(['fol_pagoimg'=>$rutaPublica."/".$nombre]);
        ##### Resetea variables            
        $this->reset(['SubeComprTipo','SubeComprobPago']);
    }

    public function borrarimg($idfolio){
        FoliosModel::where('fol_id',$idfolio)
        ->update([
            'fol_pagoimg'=>null
        ]);
    }

    public function borrarPedido($idfolio){
        FoliosModel::where('fol_id',$idfolio)
            ->update([
                'fol_act'=>'0'
            ]);
        FoliosProdsModel::where('ped_folio',$idfolio)
            ->update([
                'ped_act'=>'0'
            ]);  
    }

    public function borrarProducto($idProd){
        FoliosProdsModel::where('ped_id',$idProd)
            ->update([
                'ped_act'=>'0'
            ]);
        
    }


    public function render()  {
        $mesHoy=Date("m");
        $anioHoy=Date("Y");

        $this->prods=DB::table('folios_prods')
            ->where('ped_act','1')
            ->where('ped_usrid',auth()->user()->id)
            ->get();

        $foliosAct=DB::table('folios')
            ->where('fol_act','1')
            ->where('fol_usrid', auth()->user()->id)
            ->where('fol_mes','>=',$mesHoy)
            ->where('fol_anio','>=',$anioHoy)
            ->get();

        $foliosInact=DB::table('folios')
            ->where('fol_act','1')
            ->where('fol_usrid', auth()->user()->id)
            ->where(
                function($q){
                    $mesHoy=Date("m");  $anioHoy=Date("Y");
                    return $q
                    ->where('fol_mes','<',$mesHoy)
                    ->where('fol_anio',$anioHoy);
                })
            ->orWhere('fol_anio','<',$anioHoy)              
            ->get();

        
        if($this->GranVariable=='activos'){
            $this->folios=$foliosAct;
            return view('livewire.mispedidos-livewire-component', [
                'mesHoy'=>$mesHoy, 
                #'folios'=>$folios,
                #'prods'=>$this->prods,
            ]);

        }elseif($this->GranVariable=='inactivos') {
            $this->folios=$foliosInact;
            return view('livewire.mispedidos-livewire-component', [
                'mesHoy'=>$mesHoy, 
                #'folios'=>$foliosInact,
                #'prods'=>$this->prods,
            ]);
        }       

       
        
    }
}
