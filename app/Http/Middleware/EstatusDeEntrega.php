<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;
use App\Models\Calendario;
use App\Models\EstadosModel;
use Illuminate\Http\Request;
use App\Models\TrabajosModel;
use Symfony\Component\HttpFoundation\Response;

class EstatusDeEntrega
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        ##################################################################
        #################### Obtiene los próximos 5 eventos del Calendario
        $ProxEventos=Calendario::where('act','1')
        ->where('end','>=',now())
        ->where(
            function($q){
                return $q
                ->where('event','ped')
                ->orWhere('event','like','com'.'%');
            })        
        ->orderBy('start','asc')
        ->take(5)
        ->get();
        ##################################################################
        #################### Detecta si el día de hoy se toman pedidos
        $EnPedido=Calendario::where('act','1')
            ->where('start','<=',now())
            ->where('end','>=',now())
            ->where('event','ped')
            ->orderBy('start','asc')
            ->get();
            #dd($EnPedido);
        if($EnPedido->isEmpty()){
            $EnPedido='0';
        }else{
            $EnPedido='1';
        }

        ##################################################################
        #################### Detecta si el día de hoy es día de entrega
        $EnEntrega=Calendario::where('act','1')
            ->where('start','<=',now())
            ->where('end','>=',now())
            ->where('event','like','com'.'%')
            ->orderBy('start','asc')
            ->get();

        if($EnEntrega->isEmpty()){
            $EnEntrega='0';
        }else{
            $EnEntrega='1';
        }

        #dd($EnPedido,$EnEntrega, $ProxEventos[0]);
        ##################################################################
        ############ Lee el calendario y genera texto del evento que viene
        $hoy=new DateTime(today());
        $arraySemana=['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];
        $arrayMeses=['Mes','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];

        if($EnPedido=='1'){
            $FinProx=$ProxEventos[0]->end;
            $FinProx=new DateTime($FinProx);     $Dif=$FinProx->diff($hoy);
            $SigEvento="Estamos tomando pedidos (quedan ".$Dif->days." dias).";
        }else if($EnEntrega=='1'){
            $FinProx=$ProxEventos[0]->end;
            $FinProx=new DateTime($FinProx);     $Dif=$FinProx->diff($hoy);
            $SigEvento="Estamos en entrega de productos (quedan ".$Dif->days." dias).";
        }else{
            if($ProxEventos[0]->event == 'com1' OR $ProxEventos[0]->event == 'com2'){
                $InicioProx=$ProxEventos[0]->start;
                $Ini=new DateTime($InicioProx);     $Dif=$Ini->diff($hoy);
                $diaSem=$arraySemana[date("w",strtotime($InicioProx))];
                $diaMes=date("d",strtotime($InicioProx));
                $Mes=$arrayMeses[date("m",strtotime($InicioProx))];
                $SigEvento="Ya se cerró la toma de pedidos. La entrega será el ".$diaSem." ".$diaMes." de ".$Mes." (en ".$Dif->days." días)";
                
            }else if($ProxEventos[0]->event == 'ped'){
                $InicioProx=$ProxEventos[0]->start; 
                $Ini=new DateTime($InicioProx);     $Dif=$Ini->diff($hoy);
                $SigEvento="Hoy no hay toma de pedidos. La toma de pedios iniciará en ".$Dif->days." días. (".$InicioProx.")";
            }
        }
        ##################################################################
        ################## Determina vigencia del usuario y calcula tiempo
        $membrefin=auth()->user()->membrefin;
        $membrefin=new DateTime($membrefin);    
        $Difmembre=$membrefin->diff($hoy);
        
        if($membrefin < today()){
            $vigencia='0';
        }else{
            $vigencia='1';
        }


        ##################################################################
        ###################### Obtiene renglón de datos  de próximo pedido
        $ProxPedido=Calendario::where('act','1')
            ->where('end','>=',now())
            ->where('event','ped')
            ->orderBy('start','asc')
            ->first();
        
        ##################################################################
        ############## Obtiene renglón de datos  de próxima primer entrega
        $ProxCom1=Calendario::where('act','1')
            ->where('end','>=',now())
            ->where('event','com1')
            ->orderBy('start','asc')
            ->first();

           
        ##################################################################
        ############# Obtiene renglón de datos  de próxima segunda entrega
        $ProxCom2=Calendario::where('act','1')
            ->where('end','>=',now())
            ->where('event','com2')
            ->orderBy('start','asc')
            ->first();

        
        ##################################################################
        ####### Obtiene datos de fecha de próxima primer y segunda entrega
        $fecha1=new DateTime($ProxCom1->start); 
        $dia1 =$fecha1->format("d");     
        $mes1 =$fecha1->format("m");      
        $diames1 =$fecha1->format("w");   
        $anio1 =$fecha1->format("Y");
        $ProxCom1date=['diasem'=>$diames1, 'dia'=>$dia1, 'mes'=>$mes1, 'anio'=>$anio1]; #ej: ['jue', '31', 'dic', '2023'] -> [4,31,12,2023]

        $fecha2=new DateTime($ProxCom2->start); 
        $dia2 =$fecha2->format("d");
        $mes2 =$fecha2->format("m");
        $diames2 =$fecha2->format("w");
        $anio2 =$fecha2->format("Y");
        $ProxCom2date=['diasem'=>$diames2, 'dia'=>$dia2, 'mes'=>$mes2, 'anio'=>$anio2]; #ej: ['jue', '31', 'dic', '2023'] -> [4,31,12,2023]

        ##################################################################
        ################# Indica si la próxima entrega es Com1 o Com2
        if( $ProxCom1->start   >=   $ProxCom2->start ){
            $ProximaCom=['com2',$ProxCom2date];
        }else{
            $ProximaCom=['com1',$ProxCom1date];
        }

        ##################################################################
        ################# Obtiene datos de próximo inicio y fin de pedidos
        $fecha1=new DateTime($ProxPedido->start); 
        $dia1 =$fecha1->format("d");     
        $mes1 =$fecha1->format("m");      
        $diames1 =$fecha1->format("w");  
        $anio1 = $fecha1->format("Y"); 
        $ProxPedStart=['diasem'=>$diames1, 'dia'=>$dia1, 'mes'=>$mes1, 'anio'=>$anio1]; #ej: ['jue', '31', 'dic', '2024] -> [4,31,12,2024]

        $fecha2=new DateTime($ProxPedido->end); 
        $dia2 =$fecha2->format("d");
        $mes2 =$fecha2->format("m");
        $diames2 =$fecha2->format("w");
        $anio2 = $fecha2->format("Y"); 
        $ProxPedEnd=['diasem'=>$diames2, 'dia'=>$dia2, 'mes'=>$mes2, 'anio'=>$anio2]; #ej: ['jue', '31', 'dic', '2024] -> [4,31,12,2024]

       
        ##################################################################
        ######################### Calcula número de trabajos del usuario
        $finMembre = auth()->user()->membrefin; 
        $finMembre = date("Y-m-d", strtotime($finMembre));
        $iniMembre = strtotime ('-1 year' , strtotime($finMembre));
        $iniMembre = date("Y-m-d", $iniMembre);  

        $iniMembre=new DateTime($iniMembre);
        $finMembre=new DateTime($finMembre);       
        
        $trabajos=TrabajosModel::where('work_usrid', auth()->user()->id)
            ->where('work_fechatrabajo','>=',$iniMembre)
            ->where('work_fechatrabajo','<=',$finMembre)
            ->orderBy('work_fechatrabajo','asc')
            ->get();

        #################################################################
        ################## Establece estado de EnPagos
        if($EnPedido=='1'){
            $EnPagos='';
        }else{
            $EnPagos=EstadosModel::where('edo_name','EnPagos')->value('edo_edo');
        }

        ##################################################################
        ################## Establece si hay Pedidos de ocasión
        $Ocasion=Estadosmodel::where('edo_name','Ocasion')->value('edo_edo');

        ##################################################################
        ################## Establece si es tiempo de ListasDeAbasto
        $ListasAbasto=Estadosmodel::where('edo_name','ListasDeAbasto')->value('edo_edo');

        ##################################################################
        ######## Genera lista de personas que van a entregar el día de hoy
        
        $Entregadores=TrabajosModel::where('work_act','1')->where('work_fechatrabajo','=', date("Y-m-d"))->pluck('work_usrid')->toArray();
        if( in_array( auth()->user()->id, $Entregadores)){$enTrabajo='1';}else{$enTrabajo='0';}
        #dd(auth()->user()->id, $enTrabajo,$Entregadores);        
        
        ##################################################################
        ################################  Guarda variables en sesión
        session([
            'EnPedido'=>$EnPedido,          ### 0=no en pedido ó 1=si en pedido
            'EnEntrega'=>$EnEntrega,        ### 0=no en entrega ó 1=si en entrega
            'EnPagos'=>$EnPagos,            ### ""= (vacío) no en período de pagos 1=sí en período (es un checkbox)
            'Ocasion'=>$Ocasion,            ### ""= (vacío) no hay productos de ocasión, 1=si hay (es un checkbox)
            'ListasAbasto'=>$ListasAbasto,  ### ""= (vacío) no es tiempo de listas de abasto, 1=si es tiempo (es un checkbox)

            #'ProxEventos'=>$ProxEventos,    ### array de de 5 próximos eventos de calendario
            'ProxChoro'=>$SigEvento,        ### texto explicativo de situación del calendario
            'ProxPedido'=>$ProxPedido,      ### renglón de calendario del próximo pedido
            'ProxCom1'=>$ProxCom1,          ### renglón de calendario del próximo comanda1
            'ProxCom2'=>$ProxCom2,          ### renglón de calendario del próximo comanda2
            
            'ProxCom1date'=>$ProxCom1date,  ### array con [ DiaDeLaSemana DiaDelMes  Mes Anio] de próxima entrega 1
            'ProxCom2date'=>$ProxCom2date,  ### array con [ DiaDeLaSemana DiaDelMes  Mes Anio] de próxima entrega 2
            #'ProxPedstart'=>$ProxPedStart,  ### array con [ DiaDeLaSemana DiaDelMes  Mes Anio] de inicio de pedidos
            'ProxPedend'=>$ProxPedEnd,      ### array con [ DiaDeLaSemana DiaDelMes  Mes Anio] de fin de pedidos
            'ProximaCom'=>$ProximaCom,      ### Indica 2 valores: próxima comanda (com1 ó com2) e incluye ProxCom#date (array con  [ DiaDeLaSemana DiaDelMes  Mes Anio] de próxima entrega)
            #'Entregan'=>$entreganHoy,       ### Arreglo con id de usuarios que van a entregar el día de hoy
            'UsrEnTrabajo'=>$enTrabajo,      ### 1=sí está en la lista de trabajos para hoy, 0=no está en la lista de trabajos para hoy
            
            'vigencia'=>$vigencia,          ### 0=no está vigente la anualidad ó 1=sí está vigente la anualidad
            'FinMembre'=>$Difmembre->days,  ### Número de días que faltan para el vencimiento de anualidad
            'trabajos'=>$trabajos,          ### Tabla de trabajos realizados en el año previo al vencimiento
        ]);
        
        return $next($request);
    }
}
