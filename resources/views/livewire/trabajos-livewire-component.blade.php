<div>
   <h1>Lista de trabajos</h1>
   
   La Cooperativa existe gracias a la participación y al trabajo de sus Cooperativistas.
   


   




       <div class="row">
        <div class="form-group col-3">
            <label>Cooperativista:  <red>*</red></label>
            <input type="text" class="form-control @error('usuarioBusca')error @enderror" wire:model="busca" style="width:90%" placeholder="Buscar...">
            {{--@if($busca != '')--}}
                <select size="5" class="form-control" style="width:90%;" wire:model="usuarioBusca">     
                    <option value="0">Todos</option>  
                    @if(!empty($listaUsrs))
                        @foreach($listaUsrs as $i)
                            <option value="{{$i->id}}">{{$i->nombre}} {{$i->ap_pat}} {{$i->ap_mat}}</option>
                        @endforeach
                    @endif
                </select>
            {{--@endif--}}
            @error('seleccionado')<error>{{$message}}</error>@enderror
        </div>
        

        <div class="col-md-1">
            <label  class="form-label">Año:</label>
            <select  wire:model="anioBusca" class="form-control @error('anioBusca')error @enderror" value="{{old('anioBusca')}}" >
                <option value="0">Todos   </option>
                <?php $VerAnio=[]; for ( $a = '2018'; $a <= date("Y"); $a++ ){$VerAnio[]=$a;} ?>
                @foreach($VerAnio as $i)
                    <option value="{{$i}}">{{$i}}</option>
                @endforeach
            </select>
            @error('anioBusca') <error>{{$message}}</error> @enderror
            
        </div>

        <div class="col-md-2">
            <label  class="form-label">Mes:</label>
            <select  wire:model="mesBusca" class="form-control @error('mesBusca')error @enderror" value="{{old('mesBusca')}}" >
                <option value="0">Todos   </option>
                <?php $VerMes=[]; for ( $a = '1'; $a <= '12'; $a++ ){$VerMes[]=$a;} ?>
                @foreach($VerMes as $i)
                    <option value="{{$i}}">{{session('arrayMeses')[$i]}}</option>
                @endforeach
            </select>
            @error('mesBusca') <error>{{$message}}</error> @enderror
        </div>
        <div class="col-md-2">
            <label  class="form-label">Total de trabajos:</label><br>
            <div style="font-weight:bold;font-size:150%;text-align:center;">{{$trabajos->count()}}</div>
        </div>
        
        <div class="col-md-3">
            @if($usuarioBusca > '0')
            
                <div>Inscripcion: {{preg_replace("/ .*/","", $usr['inscripcion'])}}</div>
                <div>Anualidad: {{preg_replace("/ .*/","", $usr['anualidad'])}}</div>
                <div>{{$usr['antAnio']}} años,  {{$usr['antAnio']}} meses  {{$usr['antAnio']}} dias</div>
            @endif
        </div>
    </div>

    <div class="row">
        <ch>De 2015 a 2017 nos reuníamos en la calle y no existía sistema, por lo que no se registraron los trabajos.</ch>
    </div>

    


    <!-- ------------------------------- MUESTRA TABLA DE TRABAJOS ------------------------------------------- -->
    <div class="row">
        <div class="display:flex;">
            @if($trabajos->count() > 0)
                @foreach ($trabajos as $t)
                    <?php 
                        $nombre=ucfirst(strtolower($t->nombre))." ".ucfirst(strtolower($t->ap_pat));
                        $anio=preg_replace("/-.*/","", $t->work_fechatrabajo);     
                        $mes= preg_replace("/^....-/","", $t->work_fechatrabajo); 
                        $mes=intval(preg_replace("/-..$/","", $mes));
                        $trab=new DateTime($t->work_fechatrabajo);
                        $hoy=new DateTime("today");
                        if($trab == $hoy){
                            $col="rgb(207, 207, 207)";
                            $col2="black";
                        }else if($trab > $hoy){
                            $col="white";
                            $col2="green";
                        }else{
                            $col="aliceblue";
                            $col2="black";
                        }
                                
                    ?>
                    <div class="" style="display:inline-block;width:200px; height:100px; margin:1rem; padding:1rem; border:2px solid {{$col2}}; background-color:{{$col}};">
                        <div style="font-weight:bold;">{{substr($nombre,0,15)}}</div>
                        <div style="font-size:80%;">{{$anio}} {{session('arrayMes')[$mes]}}</div>   
                        <div style="font-size:80%;color:gray;">{{substr($t->work_descripcion,0,15)}}</div>
                    </div>
                @endforeach
            @else
                <div style="margin:1rem; padding:1rem;"> No hay trabajos registrados de {{$nombre}}</div>
            @endif
        </div>
    </div>


    <!-- ------------------------------ REGISTRAR NUEVO TRABAJO ------------------------------------------ -->
    @if(in_array(auth()->user()->priv,  $clubToby))
        <div class="row">
            <div style=" margin:1rem; background-color:aliceblue; padding:2rem;">
                <div class="col-md-2 col-sm-11 form-group">
                    <label> Registrar trabajo de<br> {{$usrdata->nombre}}</label>
                </div>
                <!-- ------------------------------- FECHA DEL TRABAJO --------------------------- -->
                <div class="col-md-2 col-sm-11 form-group">
                    <label>Fecha: <red>*</red></label>
                    <input type="date" wire:model="FechaTrabajo"  class="form-control @error('FechaTrabajo')error @enderror" value="{{old('FechaTrabajo')}}">
                    @error('FechaTrabajo')<error>{{$message}}</error> @enderror 
                </div>
                <!-- ------------------------------- DESCRIPCIÓN DEL TRABAJO --------------------------- -->
                <div class="col-md-4 col-sm-11 form-group">
                    <label>Descripción: <red>*</red></label>
                    <input type="text" class="form-control @error('DescripTrabajo') @enderror" wire:model="DescripTrabajo" placeholder="(entrega, recepcion, otro)" value="{{@old('Descriptrabajo')}}">
                    @error('DescripTrabajo')<error>{{$message}}</error> @enderror
                </div>
                <!-- ------------------------------- BOTÓN DE GUARDAR --------------------------- -->
                <div class="col-md-4 col-sm-11">
                    <button type="button" class="btn btn-primary" wire:click="RegistraTrabajo">
                        <i class="bi bi-plus-square-fill"></i> 
                        Registrar algún trabajo o apoyo
                    </button>
                </div>
            </div>
        </div>
    @endif




    @push('scripts')
        <script type="text/javascript">  
           
        </script>
    @endpush
</div>
