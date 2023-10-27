<div>

<form method="post">
    @csrf
    <?php $ped=session('ProxPedido'); $com1=session('ProxCom1'); $com2=session('ProxCom2'); ?>
    <div class="row" style="text-align:left;" > 
        <!-- ----------------------------Texto de aviso de estado -------------------------------------------------- -->
        @if(session('EnPedido')=='1') 
            <?php
                $hoy=new DateTime(today()); 
                $Fin=new DateTime(session('ProxPedido')['end']);
                $Dif=$Fin->diff( $hoy );
            ?>            <h1>Pre-pedido para {{session('arrayMeses')[$ped->mes]}} </h1>
            <div>
                <div class="col-lg-6 col-md-6 col-sm-12">Primer entrega:  {{session('arraySemana')[session('ProxCom1date')['diasem']]}} {{session('ProxCom1date')['dia']}} de {{session('arrayMeses')[session('ProxCom1date')['mes']]}}  </div> 
                <div class="col-lg-6 col-md-6 col-sm-12">Segunda entrega: {{session('arraySemana')[session('ProxCom2date')['diasem']]}} {{session('ProxCom2date')['dia']}} de {{session('arrayMeses')[session('ProxCom2date')['mes']]}}  </div>
            </div>
        @else
            <?php 
                $fecha=new DateTime($ped->start); 
                $dia =$fecha->format("d");
                $mes =$fecha->format("m");
                $mes2 =$fecha->format("w");   #$diaSem=$arraySemana[date("w",strtotime($InicioProx))];
            ?>
            <h1>Lo sentimos, el próximo período de pre-pedidos <br>para {{session('arrayMeses')[$ped->mes]}}
                iniciará el {{session('arraySemana')[$mes2]}} {{$dia}} de {{session('arrayMeses')[$mes]}} </h1>
        @endif
        
        <!-- --------------------------------- MUESTRA NOMBRE Y ESTADO DE COBRO ----------------------------- -->
        @if(auth()->user()->estatus == 'act' )
            Cooperativista activo (admin): 
        @elseif(auth()->user()->estatus == 'pru' )
            Usuario de prueba:     
        @else
            Cooperativista registrado:
        @endif
        {{auth()->user()->nombre}} {{auth()->user()->ap_pat}}
    </div>
    <br>

    <!-- -------------------------------------- MUESTRA BOTONES DE PAGO O DE ANUALIDAD -------------------------- -->
    <div class="sticky-top row" style="padding:2rem; background-color:white;">
        <div class="col-lg-10 col-md-10 col-sm-10" style="font-size:3rem; text-align:right;">
            Total $ <span id="GranTotal">0</span>   <input type="hidden" name="total" id="totalEsconde">          
     
            @if(session('vigencia')=='1')
                @if(session('EnPedido')=='1')       
                    <button class="btn btn-primary" type="submit" ><i class="bi bi-cart-fill"></i> Hacer Prepedido</button> 
                @else
                    -- No es tiempo de tomar pedidos--
                @endif
            @else
                <!-- -------------------------------- Inicia tabla de pago de anualidad ------------------------------- -->
                <div class="col-md-12 col-sm-12" style="background-color: rgb(241, 241, 241); padding:1rem; margin:1rem; text-align:left; margin:1rem;">
                    <?php
                        $anual="150"; $multa="250";
                        $fin = auth()->user()->membrefin; 
                        $ini = strtotime ('-1 year' , strtotime($fin));
                        $fin = date("Y-m-d", strtotime($fin));
                        $ini = date("Y-m-d", $ini);
                        $trabs=session('trabajos');
                        $numero=count($trabs);
                    ?>
                    @if(auth()->user()->estatus == 'pru')
                        <div style="margin:5px;">
                            <h2>Pago de anualidad</h2>
                            Ya venció tu mes de prueba.<br>
                            Debes pagar la anualidad (${{$anual}}) y comprometerte a cubrir dos cuotas de trabajo al año.<br>
                            Por cada cuota de trabajo que no cubras  deberás pagarás ${{$multa}} pesos. <br>
                        </div>
                        <?php $PagaAnual=$anual; ?>
                        
                    @else
                        <h2>Pago de anualidad</h2>
                        <p>Debes pagar tu anualidad de ${{$anual}} pesos y cubrir dos cuotas de trabajo al año. Por cada cuota de trabajo que no cubras, pagarás ${{$multa}} pesos.</p>
                        <p>Tu anualidad vencida inició el {{$ini}} y terminó el {{$fin}}.</p>
                        
                        <div class="row">                              
                            <div class="col-md-4">
                                Trabajos registrados:<br>
                                @foreach($trabs as $t)
                                    <li> {{$t->work_fechatrabajo}} (folio {{$t->work_id}})
                                @endforeach
                            </div>

                            <div class="col-md-8" style="border-left: 1px double black;">
                                En este período, 
                                @if( $numero == 0) <?php $PagaAnual=$anual+$multa+$multa;?> <b>no tienes ningun</b> trabajo.<li>Anualidad: ${{$anual}}<li>Trabajo 1: ${{$multa}}<li>Trabajo 2: ${{$multa}}<br><b>Total = ${{$PagaAnual}}</b>.
                                @elseif($numero =='1') <?php $PagaAnual=$anual+$multa;?> <b>solo tienes uno</b> de los dos trabajos registrados.<br> Pagarás:<li>Anualidad: ${{$anual}}<li>Primer Trabajo: $0<li>Segundo Trabajo: ${{$multa}}<br><b>Total = ${{$PagaAnual}}</b>.<br>
                                @else tienes {{$numero}} <?php $PagaAnual=$anual;?> trabajos registrados.<br> Pagarás:<li>Anualidad: ${{$anual}}<li>Primer Trabajo: $0<li>Segundo Trabajo: $0<br><b>Total = ${{$PagaAnual}}</b>. 
                                @endif 
                            </div>
                          
                        </div>
                        <div class="row">
                            <div class="col-md-12">Si detectas algún error, avisa a la administración. Para continuar, haz clic en el botón de pago y continúa con la toma de tu pedido.</div>
                        </div>
                    @endif

                    <div style="margin:1rem;text-align:right; "> 
                        <button class="btn btn-danger" type="button" onclick="subtotal('Anualidad','com1','{{$PagaAnual}}'); ejecutarBotones();" id="BotonDePagoDeAnualidad">
                            <i class="bi bi-arrow-repeat"></i> Pagar ${{$PagaAnual }} de anualidad
                        </button>

                        <button class="btn btn-primary" type="submit" id="botonPedidoPorAnualidad" style="display:none;"> <!-- botón de prepedido para quienes están pagando anualidad -->
                            <i class="bi bi-cart-fill"></i> Hacer Prepedido
                        </button> 
                    </div>
                </div>
                <!-- -------------------------------- Termina tabla de pago de anualidad ------------------------------- -->
            @endif
        </div>
    </div>

   

    <!-- -------------------------------- Inicia tabla de productos ------------------------------- -->
    <table class="table table-striped table-hover" style="width:90%">
        <thead>
        <tr><th>
            <div class="col-lg-1 col-md-0 col-sm-0"></div>
            <div class="col-lg-5 col-md-4 col-sm-12">Producto</div>
            <div class="col-lg-1 col-md-2 col-sm-12">Precio {{session("usr_estatus")}}</div>
            <div class="col-lg-2 col-md-3 col-md-12">Pedido</div>
            <div class="col-lg-1 col-md-2 col-sm-12" style="text-align:right;">Subtotal</div>
        </th></tr>
        </thead>
        <tbody>
            <!-- ---------------- inicia línea de anualidad ------------------x---- -->
            @if(session('vigencia')=='0')
                <tr>
                    <td style="display:none;" id="renglonAnualidad">
                        <div class="col-lg-1 col-md-0 col-sm-0"> &nbsp; </div>

                        <div class="col-lg-5 col-md-4 col-sm-12">
                            <span style="font-size:1.7rem; font-weight:bold;"> 
                                Pago de anualidad de {{auth()->user()->usr}}</i><span style="color:gray"> valido por un año a partir de el día de pago </span>                    
                            </span>                                 
                        </div>
                        <div class="col-lg-1 col-md-2 col-sm-12">$ {{$PagaAnual}}</div>

                        <div class="col-lg-2 col-md-3 col-md-12">
                            <input type="hidden" name="Anualidad" value="{{$PagaAnual}}">
                            <input type="hidden" id="com1_Anualidad" value="1">
                            <input type="hidden" id="com2_Anualidad" value="0">
                        </div>
                        <div class="col-lg-1 col-md-2 col-sm-12" style="text-align:right;">
                            
                            <div style="font-size:2rem; color:var(--cuadroTotal); ">
                                $ <b><span class="CalculadoraSubtotal" id="subtot_Anualidad" >0</span></b>
                            </div>
                        </div>
                    </td>
                </tr>
            @endif

            @foreach($todo as $key=>$value)
                <?php
                    #### Genera vector de sabores   
                    $sabores=explode(',', $value->variantes);
                    $num=count($sabores);
                    ### Quita palabras repetidas de nombre (cuando ya está en gpo)
                    $value->nombre = preg_replace("/$value->gpo/i","",$value->nombre);
                    ### Determina precio
                    if( auth()->user()->estatus == 'act'){$precio = $value->precioact;}else{ $precio = $value->precioreg; }
                    ### Determina comportamiento según entrega:
                    if($value->entrega == 'com1'){
                        $com1act=""; $com1text="Com 1"; $com2act="disabled"; $com2text="";
                    }else if($value->entrega == 'com2'){
                        $com1act="disabled"; $com1text=""; $com2act=""; $com2text="Com 2";
                    }else if($value->entrega =='com12'){
                        $com1act=""; $com1text="Com 1"; $com2act=""; $com2text="Com 2";
                    }else if($value->entrega == 'comid'){
                        $com1act=""; $com1text="Com ID x 2"; $com2act="disabled"; $com2text="Com ID x 2";
                    }
                ?>
                @foreach($sabores as $sabor)
                    <tr>
                        <td>
                            <!-- ---------------------- Espacio en blanco ----------------------- -->
                            <div class="col-lg-1 col-md-0 col-sm-0"></div>

                            <!-- ---------------------- Nombre   ----------------------- -->
                            <div class="col-lg-5 col-md-4 col-sm-12">
                                <span onclick="VerNoVer('{{$key}}','{{$value->id}}{{$sabor}}');";>
                                    <i class='fa fa-info-circle' style='font-size:18px;color:#BDBDBD' ></i>
                                    <span style="font-size:1.7rem; font-weight:bold;">{{$value->gpo}} {{$value->nombre}} 
                                        <span style="color:gray">{{$sabor}}</span>
                                    </span>                                
                                </span>
                            </div>
                            
                            <!-- ---------------------- Precio   ----------------------- -->
                            <div class="col-lg-1 col-md-2 col-sm-12">
                                <span style="font-size:1.7rem; color:var(--cuadroTotal);">
                                    $ {{$precio}}
                                    @if($value->entrega=='comid')
                                    <span style="color:gray;"> x2</span>
                                    @endif
                                </span>
                            </div>

                            <!-- ---------------------- Celdas de pedidos ------------------ -->
                            <div class="col-lg-2 col-md-3 col-md-12">
                                <div style="display:flex;">  
                                    <!-- ------- Primer celda de pedido com1 ---------- -->
                                    <div style="display:flex;"> 
                                        <input type='number' class='producto' name="com1_{{$value->id}}@-{{$sabor}}" id="com1_{{$value->id}}{{$sabor}}" onkeyup="subtotal('{{$value->id}}{{$sabor}}','{{$value->entrega}}','{{$precio}}');" onchange="subtotal('{{$value->id}}{{$sabor}}','{{$value->entrega}}','{{$precio}}');"  min="0" step="1"  {{$com1act}}> <!--placeholder="{{$com1text}}"-->
                                    </div>
                                    <!-- ------- Segund celda de pedido com2 ---------- -->
                                    <div style="margin-left:0.5rem; display:flex;">
                                        @if($value->entrega == 'comid')
                                            <input type='number' class='producto' name="com2_{{$value->id}}@-{{$sabor}}" id="com2_{{$value->id}}{{$sabor}}" readonly>
                                        @else
                                            <input type='number' class='producto' name="com2_{{$value->id}}@-{{$sabor}}" id="com2_{{$value->id}}{{$sabor}}" onkeyup="subtotal('{{$value->id}}{{$sabor}}','{{$value->entrega}}','{{$precio}}');" onchange="subtotal('{{$value->id}}{{$sabor}}','{{$value->entrega}}','{{$precio}}');"   min="0" step="1"   {{$com2act}} >  <!-- placeholder="{{$com2text}}"-->
                                        @endif
                                        <!-- ------- Pedido mínimo ---------- -->
                                        @if($value->mintipo > 0)     
                                            <?php $faltan = $value->min ;?>
                                            <span style="border:1px solid rgb(158, 158, 158); background-color: rgb(176, 177, 160); color:darkgreen; height:50%; font-size:50%; margin:2px; border-radius:100%;padding:2px;">
                                                @foreach($YaPedido as $ya)
                                                    @if($ya->ped_prodid == $value->id)
                                                        <?php $faltan=$value->min - $ya->total; ?>
                                                    @endif
                                                @endforeach
                                                 @if($faltan >0) {{$faltan}} @endif
                                            </span>
                                        @endif
                                    </div>     
                                </div>   
                            </div>

                            <!-- ----------------------  Subtotal  ----------------------- -->
                            <div class="col-lg-1 col-md-2 col-sm-12" style="text-align:right;">   
                                <div style="font-size:2rem; color:var(--cuadroTotal); ">
                                    $ <b><span class="CalculadoraSubtotal" id="subtot_{{$value->id}}{{$sabor}}">0</span></b>
                                </div>
                            </div>

                            <!-- ---------------------- Espacio en blanco ----------------------- -->
                            <div class="col-lg-1 col-md-1 col-sm-0"></div>

                            <!--- ------------------------------- Seccion DE INFO OCULTA ------------------------------------------------>
                            <div class="col-lg-12 col-md-12 col-sm-12 " id="sale_{{$key}}{{$value->id}}{{$sabor}}" style='display:none; font-size:0.9rem; margin-top:1rem;'> 
                                <div style="overflow:auto;">
                                    @if($value->img == '' || is_null($value->img))
                                        <img src="{{ asset('/logo.png') }}" style='width:150px; margin:15px; float:left;'><br>
                                    @else                                        
                                        <!--img src={{asset("/productos/$value->img")}} style="width:150px; margin:15px; float:left;"><br-->
                                        <img src='http://coopelatina.org/sistema/img/productos/Aguacate_Barzon.jpeg' style='width:150px; margin:15px; float:left;'><br>
                                    @endif

                                    <p style="font-size:1.5rem;"> {{$value->descripcion}}</p>
                                    <p style="font-size:1.5rem;">Presentación: {{$value->presentacion}} </p>
                                    @if( auth()->user()->estatus == 'act')
                                        <div style="font-size:1.5rem;font-style:italic;display:inline-block;">Act $ {{$value->precioact}} </div>  &nbsp; &nbsp;
                                        <div style="font-size:1.5rem;font-style:italic;display:inline-block;">Reg $ {{$value->precioreg}} </div>   &nbsp; &nbsp;
                                        <div style="font-size:1.5rem;font-style:italic;display:inline-block;">Pub $ {{$value->preciopub}} </div> 
                                    @endif

                                    @if($value->mintipo == '0')
                                        <p style="font-size:1.5rem;"> Este producto no requiere ningún mínimo de volumen para que el proveedor lo traiga.</p>
                                    @elseif($value->mintipo =='1')
                                        <p style="font-size:1.5rem;"> Se requiere un mínimo de {{$value->min}} unidades de este producto para que el proveedor lo traiga.</p>
                                    @elseif($value->mintipo =='2')
                                        <p style="font-size:1.5rem;"> Se requiere un mínimo de compra de $ {{$value->min}} pesos al proveedor para que nos  traiga sus productos.</p>
                                    @endif
                                </div>

                                <div>
                                    <b> &nbsp; Productor:</b>  <a href='../productores.php#{{$value->proveedor}}' style='color: inherit; text-decoration: none' target='new'>{{$value->proveedor}}</a>
                                </div>

                                <!-- ------------------------------ BOTÓN DE EDITAR ----------------------- -->
                                @if(in_array(auth()->user()->priv,  ['root','teso','admon']))
                                    <div style="font-size:2rem;margin:1rem;">
                                        <button type="button" style="float:right;" class="btn btn-primary" wire:click="AbrirModal('edit',{{$value->id}})" data-toggle="modal" data-target="#EditarProducto">
                                            <i class="bi bi-pencil-square"></i> 
                                            Editar producto
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
    <!-- ------------------------- BOTÓN PARA INGRESAR UN NUEVO PRODUCTO ------------------- -->
    @if(in_array(auth()->user()->priv,  ['root','teso','admon']))
        <div style="font-size:2rem;margin:1rem;">
            <button type="button" class="btn btn-primary" wire:click="AbrirModal('nvo','')"  data-toggle="modal" data-target="#EditarProducto"><i class="bi bi-plus-square-fill"></i> Ingresar nuevo producto</button>
        </div>
    @endif
</form>


<!-- --------------------------------------------------------------------------------------------------------- -->
<!-- --------------------------------------------- MODAL PARA EDITAR o AGREGAR PRODUCTO -------------------------- -->
<!-- --------------------------------------------------------------------------------------------------------- -->
<div class="modal fade" id="EditarProducto" tabindex="-1"  wire:ignore.self >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(51, 51, 173);color:white;">
                <div class="row container" >
                    <H1> {{$text1}} producto {{$prod_id}}</H1>
                </div>
            </div>
            
            <div class="modal-body">

                <form method="POST"> 
                    @csrf 
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
                    $this->mintipo=$datos->min;
                    $this->proveedor=$datos->proveedor;
                    $this->categoria=$datos->categoria;
                    $this->responsable=$datos->responsable;
                    $this->descripcion=$datos->descripcion;
                    $this->img=$datos->img;
                    $this->orden=$datos->orden;
{{--                    <div class="row">
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
                            <input type="text" class="form-control @error('respon')error @enderror" wire:model.defer="respon"  value="{{old('respon')}}" >
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
--}}
                </form>         
            </div>

            <div class="modal-footer"> 
                <div class="row" style="padding: 1rem; ">
                    <div class="col-md-4">
                        <button type="reset" class="btn btn-secondary"  data-dismiss="modal" style="margin:5px;"><i class="fa fa-close"></i> Cerrar</button></a>
                    </div>
                    <div class="col-md-4">
                        @if($text1=='Nuevo')
                            <button type="button" id='CierraModal' wire:click="GuardaElNuevo"  class="btn btn-success" style="margin:5px;"><i class="fas fa-plus"></i> Agregar producto</button>
                        @elseif($text1=="Editar")
                            <button type="button" id='CierraModal' wire:click.defer="GuardaEdita()" class="btn btn-success" style="margin:5px;"><i class="fas fa-plus"></i> Editar evento </button>
                        @endif
                    </div>
                </div>
            </form>
            </div>
        </div>

    </div>
</div>


<!-- --------------------------------------------------------------- JAVASCRIPT ------------------------------------------------------------ -->
<!-- --------------------------------------------------------------- JAVASCRIPT ------------------------------------------------------------ -->
<!-- --------------------------------------------------------------- JAVASCRIPT ------------------------------------------------------------ -->
@push('scripts')
    <script>
        function subtotal(id,entrega,precio) {
            //-------------------------------- Obtiene variables de com1, com2 ó duplica si es comid
            var n1 = document.getElementById('com1_'+id).value
            if(entrega=="comid"){ document.getElementById('com2_'+id).value = n1 }
            var n2 = document.getElementById('com2_'+id).value
            //-------------------------------- Corrige valores nulos
            if(n1 == null  || n1 == undefined  || n1 == "")  {n1="0";}
            if(n2 == null  || n2 == undefined  || n2 == "")  {n2="0";}
            //-------------------------------- Cacluca el subtotal y lo manda al <span id=subtotal>
            var subtotal =  (parseFloat(n1) + parseFloat(n2)) * parseFloat(precio);
            document.getElementById('subtot_'+id).innerHTML = subtotal
            //--------------------------------  Calcula gran total
            cadaUno=document.getElementsByClassName('CalculadoraSubtotal');
            var x=0; var tot=0;
            for (i=0, max=cadaUno.length; i<max; i++) {
                x = parseFloat(cadaUno[i].innerHTML);
                if(isNaN(x) || x=="") {x=0;}
                tot += parseFloat(x);
            }
            document.getElementById('GranTotal').innerHTML = tot;
        }

        function ejecutarBotones(){
            var x = document.getElementById('renglonAnualidad');
            x.style.display = "block";
            
            var x = document.getElementById('botonPedidoPorAnualidad');
            x.style.display = "block";
            
            var x = document.getElementById('BotonDePagoDeAnualidad');
            x.style.display = "none";
            
        }

    </script>

@endpush
</div>
