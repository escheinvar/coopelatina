<?php

namespace App\Http\Livewire;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Calendario;
use Illuminate\Http\Request;
use App\Models\TrabajosModel;

class CalendarioLivewireComponent extends Component
{
    public $tipo='', $opciones="", $nombre="", $inicio, $fin, $respon, $idva, $responsables;
    public $opt, $text1, $desactiva, $mesPedido, $VerTabla='', $MesEntrega, $anio;
    
    

    ############################################################ Diferencía entre nuevo usr y editar usr
    public function  defineType($accion,$MyId){
        $My_id=$MyId;
        if($accion == 'nuevo'){  
            $this->reset('tipo','respon','inicio','fin','nombre','opciones','MesEntrega','anio');

            $this->text1= 'Agregar';
            $this->id = '0';
            
        }else if($accion == 'edita'){
            $this->text1= 'Editar'; 
            $valores = Calendario::where('id',$My_id)->first();
            $this->idva = $My_id;
            $this->tipo = $valores->event;
            $this->respon = $valores->responsable;
            $this->inicio = $valores->start;
            $this->fin = $valores->end;
            $this->nombre = $valores->titulo;
            $this->opciones = $valores->opciones;
            $this->MesEntrega =$valores->mes;
            $this->anio=$valores->anio;
        }
    }

    public function Borrar($MyId){
        Calendario::where('id',$MyId)->update(['act'=>'0']);
        #$this->emit('alerta','Borrado','Exitosamente');
    }

    protected $rules =[
        'tipo' => 'required',
        'inicio'=>'required|date',
        'nombre'=>'required',
        #'inicio'=>'after:tomorrow',
        'inicio'=>'required',
    ];

    public function GuardaElNuevo(){
        $this->validate();
        
        $fechaInicio= new DateTime($this->inicio);
        if($this->fin == ''){$this->fin=$this->inicio;}    
        $fechaFin=new DateTime($this->fin);

        if($fechaFin < $fechaInicio){$fechaFin=$fechaInicio;}

        Calendario::create([
            'anio'=>$this->anio,
            'mes'=>$this->MesEntrega,
            'event' => $this->tipo,
            'titulo' => $this->nombre, 
            'act'=>'1',
            'start' => $fechaInicio->format("Y-m-d H:i:s.u"),
            'end' => $fechaFin->format("Y-m-d H:i:s.u"),
            'responsable'=>$this->respon,
            
            
            'opciones'=>$this->opciones,
        ]);
        ###### guarda trabajo
        if($this->tipo=='com1' OR $this->tipo=='com2'){
            $DatosUsr=User::where('usr',$this->respon)->first();
            #dd($this->respon,$DatosUsr);
            
            TrabajosModel::insert([   #########################3 ojo pensar como detectar en edición!!!!! (pa borrar el trabajo)
                'work_act'=>'1',
                'work_usrid'=>$DatosUsr->id,
                'work_usr'=>$DatosUsr->nombre." ".$DatosUsr->ap_pat." ".$DatosUsr->ap_mat,
                'work_responsableid'=>auth()->user()->id,
                'work_mes'=>$this->MesEntrega,
                'work_anio'=>$this->anio,
                'work_fechatrabajo'=>$fechaInicio->format("Y-m-d"),
                'work_descripcion'=>'entrega Responsable',
    
            ]);
        }

        $this->emit('alerta','Guardado','Exitosamente');
    }

    public function GuardaEdita($MyId){
        #dd($MyId);
        $this->validate();
        $this->id = $MyId;
        $fechaInicio= new DateTime($this->inicio);
        $fechaFin=new DateTime($this->fin);
        if($fechaFin < $fechaInicio){$fechaFin=$fechaInicio;}
        
        Calendario::where('id',$MyId)->update([
            'anio'=>$this->anio,
            'mes'=>$this->MesEntrega,
            'event' => $this->tipo,
            'titulo' => $this->nombre, 
            'act'=>'1',
            'start' => $fechaInicio->format("Y-m-d H:i:s.u"),
            'end' => $fechaFin->format("Y-m-d H:i:s.u"),
            'responsable'=>$this->respon,
            'opciones'=>$this->opciones,
        ]);
        $this->emit('alerta','Guardado','Exitosamente');
    }
   
    public function AvanzaNombre(){
        if($this->fin == ''){  $this->fin = $this->inicio;  }
        ## obtiene mes
        $fechaAB=new DateTime($this->inicio );
        $mes=$fechaAB->format("n");
        $this->anio=$fechaAB->format("Y");
        
        
        if($this->tipo == 'com1'){
            $this->nombre="1a ".session('arrayMes')[$mes]." ".$this->respon;
        }else if($this->tipo == 'com2'){
            $this->nombre="2a ".session('arrayMes')[$mes]." ".$this->respon;
        }else if($this->tipo == 'ped'){
            if($mes <='11'){$mes = $mes + 1;}else{$mes='1'; $this->anio = $this->anio+1;}  ################# OJO AQUI!!! AGREGUÉ UN DIA AL MES Y UNO AL AÑO EN DICIEMBRE;
            $this->nombre="Pedidos de ".session('arrayMeses')[$mes];
        }
        $this->MesEntrega = $mes;
        #dd($mes,$this->anio);
    }

    public function render(){
        $calendario=Calendario::where('act','1')
            ->where(
                function($q){
                    return $q
                    ->where('start','>=', Carbon::today())
                    ->orWhere('end','>=', Carbon::today());
                })
            ->orderBy('start','asc')
            ->get(); 
        $this->responsables=User::where('estatus','act')->where('activo','1')->get();

        $eventos =[];
        ### determina colores y si es todo el día o no
        foreach ($calendario as $cal){
            if($cal->event=='com1'){
                $col="green";
                $todoDia=true;
            }elseif($cal->event=='com2'){
                $col="green";
                $todoDia=true;
            }elseif($cal->event=='ped'){
                $col="rgb(53, 53, 136)";
                $todoDia=false;
            }elseif($cal->event=='evento'){
                $col="gray";
                $todoDia=true;
            }
            $InicioDate=new DateTime($cal->start);
            $FinDate=new DateTime($cal->end);
            
            if($InicioDate->format("Ymd") == $FinDate->format("Ymd")){
                $todoDia=true;    
            }#else{
            #    $TodoElDia=false;
            #}
            
            $eventos[]=[
                'title'=> $cal->titulo,
                'start'=> $cal->start,
                'end'=> $cal->end,
                'tipo'=>$cal->event,
                'idInicial'=>$cal->id,
                'mes'=>$cal->mes,
                
                'color'=>$col,
                'allDay'=>$todoDia,
                'editable'=>false,
                'forceEventDuration'=>true,  
            ];
        }

        return view('livewire.calendario-livewire-component',compact('eventos'));
    }
}
