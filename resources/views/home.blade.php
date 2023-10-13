
@extends('plantillas.Basico')
@section('title')Inicio @endsection
@section('description') Sitio de inicio del usuario @endsection


<?php $GranVariable="conMenuHome"; ?>
@section('content')    
    
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredericka+the+Great&display=swap" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Fredericka+the+Great&family=Pangolin&display=swap" rel="stylesheet">

<style>
   
</style>

    <div class="row" style="margin:1rem;">
        <!-- ------------------------------------------------ ARRIBA ----------------------------------------------- -->
        <div class="col-md-12" style="text-align:right;">
                Hoy es {{ session('arraySemana')[0] }}  {{date('n')}} de {{session('arrayMeses')[date('j')]}} de {{date('Y')}}
        </div>
    </div>

    <div>
        <!-- ------------------------------------------------ TRABAJOS ----------------------------------------------- -->
        <a href="#" class="nolink" style="color:rgb(66, 66, 66);" onclick="VerNoVer('trabajitos','')"><i class='fas fa-hard-hat'></i> {{ count(session('trabajos')) }} de 2 trabajos</a>
        <div style="display:none;  margin-left:3rem; padding:1rem;" id="sale_trabajitos">
            <?php 
                $finMembre = auth()->user()->membrefin; 
                $finMembre = date("Y-m-d", strtotime($finMembre));
                $iniMembre = strtotime ('-1 year' , strtotime($finMembre));
                $iniMembre = date("Y-m-d", $iniMembre);  

                $hoy=new DateTime(today());
                #$FinaleMembre=new DateTime( auth()->user()->membrefin );
                $Finale=new DateTime($finMembre);     
                $Dif=$Finale->diff($hoy);

            ?>
            <p>Desde el <b>{{date("d \\d\\e M \\d\\e\\l y", strtotime($iniMembre))}}</b> y hasta <b>{{date("d \\d\\e M \\d\\e\\l y", strtotime($finMembre))}}</b> @if( session('vigencia')=='1') (quedan {{$Dif->days}} dias de anulidad). @endif <br>
            
            <p>Llevas {{ count(session('trabajos')) }} de tus 2 trabajos anuales.</p>
            
            <p>! La Cooperativa la hacemos con el trabajo de todos ¡</p>
        </div>
    </div>

    <!-- ------------------------------------------------ PIZARRÓN ----------------------------------------------- -->
    <div class="row" style="margin:1rem;">
        <div style="min-height:100px; background-color:rgb(11, 54, 11); color:white;border:10px solid gray; padding:1rem;">
            <center>
                <img src="{{ asset('/logo.png') }}" style="width:50px; background-color:white; border-radius:50px; padding:5px; ">
                <span style="font-family: 'Fredericka the Great', serif; font-size:30px;">Los avisos de la Coope</span>
                <img src="{{ asset('/logo.png') }}" style="width:50px; background-color:white; border-radius:50px; padding:5px; ">
            </center> <br>


            <div class="row" style="margin:1rem; font-family:'Pangolin',cursive; ">
                <!-- ------------------------------------------------ arriba izquierda ----------------------------------------------- -->
                <div class="col-md-4 col-sm-12" style="padding:1rem;" >
                    <div style="width:95%; margin:1rem; padding:1rem; text-align:center; border-radius:4rem;">
                       <li> {{session('ProxChoro')}}
                    </div>
                </div>
        
                <!-- ------------------------------------------------ arriba centro ----------------------------------------------- -->
                <div class="col-md-4 col-sm-12" style="padding:1rem;">
                    <div style="width:95%; margin:1rem; padding:1rem; text-align:center; border-radius:4rem;"-->
                        <li> No has registrado ninguna de tus dos cuotas de trabajo anuales.
                    </div>
                </div>
        
                <!-- ------------------------------------------------ arriba derecha ----------------------------------------------- -->
                <div class="col-md-4 col-sm-12" style="padding:1rem;">
                    <div style="width:95%; margin:1rem; padding:1rem; text-align:center; border-radius:4rem;">
                        <!-- --------------- datos de anualidad -------------- -->
                        @if(session('vigencia') == '0')
                            <li> Ya venció tu anualidad hace {{session('FinMembre')}} días.
                        @elseif(session('FinMembre') <= '45')
                            <li> Tu anualidad está por vencer. Le quedan {{session('FinMembre')}} días.
                        @else
                            <?php 
                                $fecha=new DateTime(auth()->user()->membrefin);
                                $diaMes=date("d",strtotime(auth()->user()->membrefin));
                                $Mes=session('arrayMeses')[date("m",strtotime(auth()->user()->membrefin))];
                            ?>
                            <li> Anualidad vigente (vence el {{$diaMes}} de {{$Mes}}).
                        @endif 
                    
                    </div>
                </div>
            </div>  
        

            <div class="row" style="margin: 1rem;">
                <div class="col-md-3 col-xs-0">

                </div>

                <!-- --------------- Producto de ocasión ---------------------- -->
                <div class="col-md-2 col-sm-12" style="border:7px solid white; background-color:rgb(215, 255, 196); border-radius:5px; color:black; ">
                    <div style=" color:rgb(59, 59, 59); font-family: 'Fredericka the Great', serif; text-align:center;">
                        Producto de ocasión
                    </div >
                    <div style="font-family: 'Pangolin', cursive; text-align:center; margin:7px;">  
                        ! Vamos a tener<br> Cebollas y<br> Duraznos ¡<br>
                        <a href="/prepedido" class="nolink" style="color:rgb(59, 59, 59);"><button type="button"> ! Pedir ¡</button></a>
                    </div>   
                </div>

                <div class="col-md-1 col-xs-0">

                </div>

                <!-- --------------- Datos de depósito ---------------------- -->
                <div class="col-md-4 col-sm-12" style="border:7px solid white; background-color:rgb(201, 196, 255); border-radius:5px; color:black;">
                    <div style=" color:rgb(59, 59, 59); font-family: 'Fredericka the Great', serif; text-align:center;">
                        Datos para los depósitos:
                    </div >
                    <div style="font-family: 'Pangolin', cursive; text-align:center; margin:10px;">
                        Banco: BBVA<br>
                        CARMEN ORTIZ CERVANTES<br>
                        No. cta: 046 313 2536<br>
                        CLABE: 0121 8000 4631 325369<br>
                        Avisar a Carmen al: 55 21 82 74 34<br>
                        
                    </div>   
                </div>
            </div>
            <br>
            <div class="row" style="margin:1rem;">
                <div>
                    Se organiza visita a las Chinanpas de San Gregorio!!
                </div>
            </div>
        </div><!-- ----- fin de pizarrón -------- -->
    </div>
    

    

    

@endsection

