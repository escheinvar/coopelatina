<?php

namespace App\Http\Livewire\Admon;

use App\Models\User;
use Livewire\Component;
use App\Models\CajaModel;
use App\Models\EstadosModel;
use App\Models\FoliosModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class PagoPedidosLiveComponent extends Component
{
    use WithFileUploads;
    public $pedidos, $prods, $SubeComprobPago, $SubeComprTipo, $destinoLanas;
    public $EnPagos;
    public $petitCmte=['root','teso'];
    public $VerMes, $MesesExistentes, $VerEstado;
    
    
    public function SubirPago(Request $request){
        
        ##### Valida campos
        $this->validate([
            'SubeComprTipo'=>'required',
            'SubeComprobPago'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:102400',
        ]);
        
        ##### Genera nombre
        $ArchMes=preg_replace("/.*_/","", $this->SubeComprTipo);
        $ArchFolio=preg_replace("/_.*/","", $this->SubeComprTipo);
        $nombre="ComproPago".sprintf('%04d', $ArchFolio);
        $rutaStorage="public/".$ArchMes;
        $rutaPublica="/storage/".$ArchMes;
        
        #dd($rutaStorage,$nombre);

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

    public function RegresarAprepedido($idfolio){
        ##### Cambia estado del pedido
        FoliosModel::where('fol_id',$idfolio)->update(['fol_edo'=>'4']);

        ##### Detecta el pago en caja
        $pago=CajaModel::where('caja_act','1')
            ->where('caja_tipo','Prepedido')
            ->where('caja_descripcion',$idfolio)
            ->first();
        ##### Edita el registro de caja
        CajaModel::insert([
            'caja_caja'=>$pago->caja_caja * -1,
            'caja_teso'=>$pago->caja_teso * -1,
            'caja_banco'=>$pago->caja_banco * -1,
            'caja_responsable'=>auth()->user()->id,
            'caja_usrid'=>$pago->caja_usrid,
            'caja_tipo'=>'cancela prepedido',
            'caja_descripcion'=>$pago->caja_id,
            'caja_observaciones'=>"Se cancela pedido folio ".$idfolio,
        ]);
        #dd($pago->caja_banco, $idfolio);
    }

    public function AceptarPrepedido($idfolio,$Monto,$usrId,$FolEdo){
        ##### Detecta la caja de destino
        if ( $this->destinoLanas == 'caja' ){     $columnaCaja='caja_caja'; 
        }else if($this->destinoLanas=='banco'){   $columnaCaja='caja_banco';
        }else if($this->destinoLanas=='teso'){    $columnaCaja='caja_teso';}

        ##### Renueva la anualidad (en su caso)
        if($FolEdo == '5'){
            ##### Calcula 1 año 5 dias desde hoy;
            $fecha1 = strtotime( "now" );
            $fecha2 = strtotime ('+1 year 2 days',$fecha1);
            $finMembre = date("Y-m-d 00:00:00.000",  $fecha2 );

            ##### Pasa prueba a registrado 
            $estatus=User::where('id',$usrId)->value('estatus');
            if($estatus == 'pru'){$estatus='reg';}
            
            User::where('id',$usrId)->update([
                'activo'=>'1',
                'membrefin'=>$finMembre,
                'estatus'=>$estatus,
            ]);
        }
        ##### Genera registro en caja
        CajaModel::insert([
            $columnaCaja=>$Monto,
            'caja_responsable'=>auth()->user()->id,
            'caja_usrid'=>$usrId,
            'caja_tipo'=>'Prepedido',
            'caja_descripcion'=>$idfolio, #ojo: caja_descripcion, luego se usa en la función RegresarAprepedido (no mover)
            'caja_observaciones'=>'Pago de prepedido folio '.$idfolio.' estado '.$FolEdo,
        ]);
        ##### Cambia estado del pedido
        FoliosModel::where('fol_id',$idfolio)->update(['fol_edo'=>'3']);
        

    }

    public function RechazarPrepedido($idfolio){
         ##### Cambia estado del pedido
         FoliosModel::where('fol_id',$idfolio)->update(['fol_edo'=>'0']);
    }

    public function DesRechazar($idfolio){
         ##### Cambia estado del pedido
         FoliosModel::where('fol_id',$idfolio)->update(['fol_edo'=>'4']);

    }

    public function CambiaEstado(){
        if(session('EnPagos') == ''){$nvo='1';}else{$nvo='';}
        EstadosModel::where('edo_name','EnPagos')->update(['edo_edo'=>$nvo]);
        session(['EnPagos'=>$nvo]);
        
        ###### Genera folio de tienda
        FoliosModel::firstOrCreate(['fol_anio'=>session('ProxCom2date')['anio'], 'fol_mes'=>session('ProxCom2date')['mes'], 'fol_usrid'=>'0'],[
            'fol_act'=>'1',
            'fol_edo'=>'4',
            'fol_anio'=>session('ProxCom2date')['anio'],
            'fol_mes'=>session('ProxCom2date')['mes'],
            'fol_usrid'=>'0',
            'fol_usr'=>'Coope',
        ]);
        
    }



    public function render() {
        #dd(session());
        $this->EnPagos=session('EnPagos');
        $mesHoy=Date("m");
        $anioHoy=Date("Y");
        
        $this->MesesExistentes=FoliosModel::where('fol_act','1')->distinct('fol_mes')->get();
        if($this->VerEstado=='%' ) {   $a='>=';$b='0';
        }elseif($this->VerEstado=='4'){$a='>=';$b='4';
        }elseif($this->VerEstado=='3'){$a='=';$b='3';
        }elseif($this->VerEstado=='0'){$a='=';$b='0';
        }else{$a='>=';$b='0';}

        $this->pedidos=DB::table('folios')
            ->join('users','fol_usrid','=','id')
            ->where('fol_act','1')
            ->where('fol_mes','LIKE',$this->VerMes)
            ->where('fol_anio','>=',$anioHoy)
            ->where('fol_edo',$a,$b)
            ->orderBy('fol_usr','asc')
            ->orderBy('fol_id','asc')
            ->get();

        /*$folios=DB::table('folios')
            ->where('fol_act','1')
            ->where('fol_mes','>=',$mesHoy)
            ->where('fol_anio','>=',$anioHoy)
            ->orderBy('fol_id','asc')
            ->orderBy('fol_id','asc')
            ->pluck('fol_id')->toArray();
        
        $this->prods=DB::table('folios_prods')
            ->where('ped_act','1')
            ->whereIn('ped_folio',$folios)
            ->get();
        */
        $this->prods=DB::table('folios_prods')
            ->where('ped_act','1')
            ->whereIn('ped_folio',DB::table('folios')
                                    ->where('fol_act','1')
                                    ->where('fol_mes','>=',$mesHoy)
                                    ->where('fol_anio','>=',$anioHoy)
                                    ->orderBy('fol_id','asc')
                                    ->orderBy('fol_id','asc')
                                    ->pluck('fol_id')->toArray()
            )
            ->get();
        ##### Obtiene estadísticas
        /*
        $NumPrepedSinPago=FoliosModel::where('fol_act','1')
            ->where('fol_mes','>=',$mesHoy)
            ->where('fol_anio','>=',$anioHoy)
            ->where('fol_edo','>=','4')
            ->where(
                function($q){
                    return $q
                    ->where('fol_pagoimg',null)
                    ->orWhere('fol_pagoimg','');
                })            
            ->count();
        $NumPreped=FoliosModel::where('fol_act','1')
            ->where('fol_mes','>=',$mesHoy)
            ->where('fol_anio','>=',$anioHoy)
            ->where('fol_edo','>=','4')
            ->count();
        $NumPed=FoliosModel::where('fol_act','1')
            ->where('fol_mes','>=',$mesHoy)
            ->where('fol_anio','>=',$anioHoy)
            ->where('fol_edo','3')
            ->count();
        $Estadisticas=['preps'=>$NumPreped,'prepsSinPago'=>$NumPrepedSinPago, 'peds'=>$NumPed];
        #dd($Estadisticas);
                */
        ##### Determina el etado
        

        return view('livewire.admon.pago-pedidos-live-component',['mes'=>$mesHoy,'anio'=>$anioHoy]);
    }
}
