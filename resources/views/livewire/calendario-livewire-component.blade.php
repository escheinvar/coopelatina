<div>
    @include('plantillas.MarcadorDeEstado')<br>
    
     
    <!-- https://fullcalendar.io/ -->
    <?php $EntregaColor="rgb(0,128,0)"; $PedidoColor='rgb(53, 53, 136)';   $EventoColor='gray'; $TextoColor="white"; ?>
    

    <!-- ---------------------------------- Calendario -------------------------------------- -->
    @if(count($eventos) == 0)
        <div>! No hay entregas ni período de toma de pedidos o eventos  programados ¡</div>
    @endif
    <div class="col-lg-8 col-md-6 col-sm-12 " id="calendario"></div>



    <!-- ------------------------------------- TABLA PARA EDICIÓN DE EVENTOS --------------------------------------- -->
    @if( in_array(auth()->user()['priv']  ,  ['root','admon','teso']) )
        <div class="col-lg-3 col-md-4 col-sm-12">
            <br>
            <h3>Editar eventos</h3>
            <div class="table-responsive">            
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tipo / Titulo</th>
                        </tr>
                    </thead>
                    @foreach($eventos as $i)
                        <tr wire:click.defer="defineType('edita', {{$i['idInicial']}} )">
                            <td style="background-color:{{$i['color']}};">
                                <div style="color:white; cursor: pointer; vertical-align:middle;" data-toggle="modal" data-target="#VerEvento">
                                    <div style="width:20%; display:inline-block;">{{session('arrayMes')[$i['mes']]}} <br> {{$i['tipo']}}  </div>                         
                                    <div style="width:60%; display:inline-block;">{{$i['title']}} </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
                
                @if(count($eventos) == 0)
                    ! No hay entregas ni período de toma de pedidos o eventos  programados ¡<br>
                    
                @endif
            </div>
            <br>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#VerEvento" style="float: right;"  wire:click="defineType('nuevo','')" >
                <i class="fas fa-plus"></i> 
            </button>
            <br><br><div style="font-size:10px;margin:1rem;"> ATENCIÓN: Solo se muestran eventos presentes y futuros (se ocultan todos los pasados)</div>
        </div>
    @endif

   
<style>

</style>

    <!-- --------------------------------------------------------------------------------------------------------- -->
    <!-- --------------------------------------------- MODAL PARA AGREGAR NUEVO EVENTO -------------------------- -->
    <!-- --------------------------------------------------------------------------------------------------------- -->
    <div class="modal fade" id="VerEvento" tabindex="-1"  wire:ignore.self >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(51, 51, 173);color:white;">
                    <div class="row container" >
                        <H1>  {{$text1}} Evento </H1>
                    </div>
                </div>
               
                <div class="modal-body">

                    <form method="POST"> 
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Tipo de evento:  <red>*</red></label>
                                <select class="form-control" wire:model.defer="tipo" value="{{old('tipo')}}" >  
                                    <option value="">Selccionar tipo</option>
                                    <option value="ped">Levantar Pedidos</option>
                                    <option value="com1">Primer Entrega</option>
                                    <option value="com2">Segunda Entrega</option>
                                    <option value="evento">Evento</option>
                                </select>
                                @error('tipo')<error>{{$message}}</error> @enderror 
                            </div>

                            <div class="form-group col-md-6">
                                <label>Responsable: </label>
                                <!--input type="text" class="form-control @error('respon')error @enderror" wire:model.defer="respon"  value="{{old('respon')}}" -->
                                <select class="form-control" wire:model.defer="respon" value="{{old('respon')}}">
                                    <option value="">Indicar responsable</option>
                                    @foreach($responsables as $i)
                                        <option value="{{$i->usr}}">{{$i->nombre}} {{$i->ap_pat}}</option>
                                    @endforeach
                                </select>
                                @error('respon') <error>{{$message}} </error>@enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Fecha Inicio: <red>*</red></label> 
                                <input type="datetime-local" wire:model.defer="inicio" min={{date("Y-m-dT00:00")}} value="{{old('inicio')}}" class="form-control @error('inicio')error @enderror" >
                                @error('inicio')<error> {{$message}}</error> @enderror
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label>Fecha Fin:</label>
                                <input type="datetime-local" class="form-control @error('fin')error @enderror" wire:model.defer="fin" value="{{old('fin')}}">
                                @error('fin')<error>{{$message}} </error> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label> <span style="cursor:pointer;background-color:rgb(249, 250, 251); border:1px solid rgb(161, 177, 188);padding:1px;" wire:click="AvanzaNombre">Etiqueta: </span>  <red>*</red> </label> 
                                <input type="text" class="form-control @error('nombre')error @enderror"  wire:model="nombre" value="{{old('nombre')}}" >
                                @error('nombre') <error>{{$message}} </error>@enderror 
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label>Observaciones:</label>
                                <input type="text" class="form-control @error('observa')error @enderror"  wire:model="observa" value="{{old('observa')}}" readonly>
                                @error('observa') <error>{{$message}} </error>@enderror 
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Mes de entrega:</label>
                                <select class="form-control" wire:model.defer="MesEntrega" value="{{old('MesEntrega')}}" >  
                                    <option value="1">(01) Enero</option>
                                    <option value="2">(02) Febrero</option>
                                    <option value="3">(03) Marzo</option>
                                    <option value="4">(04) Abril</option>
                                    <option value="5">(05) Mayo</option>
                                    <option value="6">(06) Junio</option>
                                    <option value="7">(07) Julio</option>
                                    <option value="8">(08) Agosto</option>
                                    <option value="9">(09) Septiembre</option>
                                    <option value="10">(10) Octubre</option>
                                    <option value="11">(11) Noviembre</option>
                                    <option value="12">(12) Diciembre</option>
                                </select>
                                @error('MesEntrega')<error>{{$message}}</error> @enderror 
                            </div>

                            <div class="form-group col-md-6">
                                <label>Año de entrega:</label>
                                <input type="number" class="form-control @error('respon')error @enderror" wire:model.defer="anio"  value="{{old('anio')}}" >
                                @error('anio')<error>{{$message}}</error> @enderror 
                            </div>
                        </div>


                        <div class="form-group col-md-12">
                            @if($this->text1 == 'Editar')
                                <button type="button" class="btn btn-light" wire:click="Borrar( {{$this->idva}})" data-dismiss="modal"><i class="bi bi-trash"> Borrar </i></button>
                            @endif
                        </div>

                    </form>         
                </div>

                <div class="modal-footer"> 
                    <div class="row" style="padding: 1rem; ">
                        <div class="col-md-4">
                            <button type="reset" class="btn btn-secondary"  data-dismiss="modal" style="margin:5px;"><i class="fa fa-close"></i> Cerrar</button></a>
                        </div>
                        <div class="col-md-4">
                            @if($text1=='Agregar')
                                <button type="button" id='CierraModal' wire:click="GuardaElNuevo"  class="btn btn-success" style="margin:5px;"><i class="fas fa-plus"></i> Agregar evento</button>
                            @elseif($text1=="Editar")
                                <button type="button" id='CierraModal' wire:click.defer="GuardaEdita( {{$this->idva}} )" class="btn btn-success" style="margin:5px;"><i class="fas fa-plus"></i> Editar evento </button>
                            @endif
                        </div>
                    </div>
                </form>
                </div>
            </div>
    
        </div>
    </div>
</div>





@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendario');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($eventos),
                locale:'es',
                displayEventTime: false,
                buttonText:{
                    today: 'Hoy',
                }
            });
            calendar.render();
        });

        $('#VerEvento').on('hidden.bs.modal', function () {
            location.reload();
        })

        $('#CierraModal').click(function() {
            $('#VerEvento').modal('hide');
        });

    </script>
@endpush
