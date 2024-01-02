<div>

<form method="post" enctype="multipart/form-data">
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
                $mes =$fecha->format("n");
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
           
     
            @if(session('vigencia')=='1')
                @if(session('EnPedido')=='1')       
                    Total $ <span id="GranTotal" style="font-size:3rem;">0</span>   <input type="hidden" name="total" id="totalEsconde">          
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

    <!-- ################################### PRODUCTOS DE OCASIÓN ################################### -->
    <!-- ################################### PRODUCTOS DE OCASIÓN ################################### -->
    <!-- ################################### PRODUCTOS DE OCASIÓN ################################### -->
    @if(session('ocasion')=='1')
    <div style="">
        <button type="button" data-toggle="modal" data-target="#PedidoOcasion" class="btn btn-success btn-lg" style="margin:1rem;">
            <i class="bi bi-gift"></i> Ver productos de ocasión
        </button><br>
    </div>
    @endif

    <!-- ################################### Inicia tabla de productos ################################### -->
    <!-- ################################### Inicia tabla de productos ################################### -->
    <!-- ################################### Inicia tabla de productos ################################### -->
    @if(session('EnPedido')=='1')
        <table class="table table-striped table-hover" style="width:90%">
            <thead>
            <tr><th>
                
                <div class="col-lg-5 col-md-4 col-sm-12">Producto</div>
                <div class="col-lg-1 col-md-2 col-sm-12">Precio {{session("usr_estatus")}}</div>
                <div class="col-lg-2 col-md-3 col-md-12">Pedido
                    <span style="border:1px solid rgb(158, 158, 158); background-color: rgb(176, 177, 160); color:darkgreen; height:60%; font-size:50%; margin:2px; border-radius:100%; padding:2px;">Mín</span>
                </div>
                <div class="col-lg-1 col-md-2 col-sm-12" style="text-align:right;">Subtotal</div>
            </th></tr>
            </thead>
            <tbody>
                <!-- ---------------- inicia línea de anualidad ------------------x---- -->
                @if(session('vigencia')=='0')
                    <tr>
                        <td style="display:none;" id="renglonAnualidad">
                            <!--div class="col-lg-1 col-md-0 col-sm-0"> &nbsp; </div-->

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
                        <?php $faltan='0';?>
                        <tr>
                            <td>
                                <!-- ---------------------- Nombre   ----------------------- -->
                                <div class="col-lg-5 col-md-4 col-sm-12">
                                    <span onclick="VerNoVer('{{$key}}','{{$value->id}}{{$sabor}}');";>
                                        <i class='fa fa-info-circle' style='font-size:18px;color:#BDBDBD' ></i>
                                        <span style="font-size:1.7rem; font-weight:bold;">{{$value->gpo}} {{$value->nombre}} 
                                            <span style="color:gray;font-size:1.6rem">{{preg_replace("/_/"," ",$sabor)}}</span>
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
                                            <?php
                                                if($value->mintipo=='1'){
                                                    $SeHaPedido=0; $faltantes='0';
                                                    foreach($YaPedido as $ya){
                                                        if($ya->ped_prodid == $value->id){
                                                            $SeHaPedido=$ya->total;
                                                        }
                                                    }
                                                    $faltantes = $value->min - $SeHaPedido;
                                                }else{$faltantes='0';}
                                            ?>
                                            
                                            @if($value->mintipo=='1' AND $faltantes >'0')
                                                <span style="border:1px solid rgb(158, 158, 158); background-color: rgb(176, 177, 160); color:darkgreen; height:70%; font-size:70%; margin:2px; border-radius:100%;padding:2px;">
                                                    {{$faltantes}}                                                
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
                                            <img src={{asset("$value->img")}} style="width:150px; margin:15px; float:left;"><br>
                                        @endif

                                        <p style="font-size:1.5rem;"> {{$value->descripcion}}</p>
                                        <p style="font-size:1.5rem;">Presentación: {{$value->presentacion}} </p>
                                        @if( auth()->user()->estatus == 'act')
                                            <div style="font-size:1.5rem;font-style:italic;display:inline-block;">Act $ {{$value->precioact}} </div>  &nbsp; &nbsp;
                                            <div style="font-size:1.5rem;font-style:italic;display:inline-block;">Reg $ {{$value->precioreg}} </div>   &nbsp; &nbsp;
                                            <div style="font-size:1.5rem;font-style:italic;display:inline-block;">Pub $ {{$value->preciopub}} </div> 
                                        @endif

                                        <p style="font-size:1.5rem;">Responsable: {{$value->responsable}}</p>
                                        
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
    
    @endif


    <!-- ################################### LISTA DE INACTIVOS Y BOTÓN PARA INGRESAR UN NUEVO PRODUCTO ################################### -->
    <!-- ################################### LISTA DE INACTIVOS Y BOTÓN PARA INGRESAR UN NUEVO PRODUCTO ################################### -->
    <!-- ################################### LISTA DE INACTIVOS Y BOTÓN PARA INGRESAR UN NUEVO PRODUCTO ################################### -->
    @if(in_array(auth()->user()->priv,  ['root','teso','admon']))
        <div style="font-size:2rem;margin:1rem;">
            <button type="button" class="btn btn-primary" wire:click="AbrirModal('nvo','')"  data-toggle="modal" data-target="#EditarProducto"><i class="bi bi-plus-square-fill"></i> Ingresar nuevo producto</button>
        </div>
        <!-- ------------ Lista de inactivos -------------- -->
        <div style="padding:2rem;">
            <h3>Productos ocultos  o sin entrega:</h3>   
            @foreach($inacts as $i)
                <li style="margin:0.7rem;padding:0.5">
                    <span style="cursor:pointer"  wire:click="AbrirModal('edit',{{$i->id}})" data-toggle="modal" data-target="#EditarProducto"> 
                        {{$i->nombre}}  
                        @if($i->entrega=='no') 
                            <span style="color:rgb(204, 101, 4);"> (Solo venta en tienda) </span> 
                        @elseif($i->entrega=='oca')
                            <span style="color:rgb(2, 175, 89);"> (Producto de Ocasión) </span>
                        @endif

                        @if($i->activo =='0')
                            <span style="background-color:rgb(221, 221, 221);"> (Oculto) </span> 
                        @endif
                    </span>
                </li>
            @endforeach
            @if(count($inacts)<1)
                No hay
            @endif
        </div>

        


        <!-- ################################### Switch de Estado de PRODUCTO DE OCASIÓN ################################### -->
        <!-- ################################### Switch de Estado de PRODUCTO DE OCASIÓN ################################### -->
        <!-- ################################### Switch de Estado de PRODUCTO DE OCASIÓN ################################### -->
        <div style="padding:2rem">
            <h3>Productos de Ocasión:</h3> 
            <label>Pedidos de ocasión</label><br>
            <label class="switch">
                <input type="checkbox" wire:model="EnOcasion" wire:click="CambiaEstadoOcasion">
                <div class="slider round"></div> 
            </label>  
            @if(session('ocasion')=='1')
                <span style="color:darkgreen;">Sí se está ofreciendo productos de ocasión.</span> 
                <h4>Productos de ocasión que se están ofreciendo:</h4>
            @else
                <span style="color:gray;">No se ofrecen productos de ocasión.</span> <!--span style="color:gray;"> Abasto Desactivo</span-->
                <h4>Productos de ocasión que se van a ofrecer cuando se active:</h4>
            @endif
            
            <div style="margin-left:70px;">
                @if(count($ocasion) == 0) -- no hay productos a ofrecer -- @endif
                <ol>
                    @foreach($ocasion as $i)
                        <li style="margin:1rem;">
                            <span style="cursor:pointer"  wire:click="AbrirModal('edit',{{$i->id}})" data-toggle="modal" data-target="#EditarProducto"> 
                                {{$i->nombre}}  
                            </span>
                            &nbsp; 
                            <span style="cursor:pointer"  wire:click="OcultaOcasion({{$i->id}})"><i class="bi bi-eye-slash"></i> </span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>


    @endif









    <!-- ######################################################################################################################################################-->
    <!-- ######################################################################################################################################################-->
    <!-- ############################################# MODAL PARA EDITAR o AGREGAR PRODUCTO  ##################################################################-->
    <!-- ######################################################################################################################################################-->
    <!-- ######################################################################################################################################################-->
    <div class="modal fade " id="EditarProducto" tabindex="-1"  wire:ignore.self >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(51, 51, 173);color:white;">
                    <div class="row container" >
                        <H1> {{$text1}} producto {{$prodid}}</H1>
                        <h3>@if($gpo == 'NUEVO'){{$gpo2}} @else {{$gpo}} @endif {{$nombre}}</h3>
                    </div>
                </div>
                
                <div class="modal-body">
                    <div style="row">
                        <!-- ------------------------------- Productor --------------------------- -->
                        <div class="form-group col-md-12 col-sm-12">
                            <label>Productor:  <red>*</red></label>
                            <select class="form-control  @error('proveedor')error @enderror" " wire:model="proveedor" value="{{old('proveedor')}}" >  
                                <option value="">Selccionar Productor</option>
                                @foreach($productores as $i)
                                    <option value="{{$i->prod_nombrecorto}}">{{$i->prod_nombrecorto}}</option>
                                @endforeach
                            </select>
                            @error('proveedor')<error>{{$message}}</error> @enderror 
                            <ch>Para agregar productores ir al <a href="/productores">catálogo</a><ch>
                        </div>
                        
                        <!--div class="col-md-1 col-sm-1">
                            <label> &nbsp; </label>
                            <a href="/productores" ><button type="button" class="btn btn-primary" data-dismiss="modal" > +</button></a>
                        </div-->
                    </div>

                    <div style="row">
                        <!-- ------------------------------- SWITCH ACTIVAR / DESACTIVAR PRODUCTO --------------------------- -->
                        <div class="form-group col-md-12 col-sm-12">
                            <?php if($activo=='0'){$activo='';} ?>

                            <label> Ocultar/Mostrar Producto</label><br>
                            <label class="switch">
                                <input type="checkbox" wire:model="activo" >
                                <div class="slider round"></div> 
                            </label>
                            @if($activo=='1') <span style="color:darkgreen;">Producto activo</span> @else <span style="color:gray;"> Producto oculto</span> @endif
                        </div>
                    </div>

                    <div class="row">
                        <!-- ------------------------------- gpo (Pronombre) --------------------------- -->
                        <div class="form-group col-md-6 col-sm-12">
                            <label>Pronombre:  <red>*</red></label>
                            <select class="form-control  @error('gpo')error @enderror"  wire:model="gpo" value="{{old('gpo')}}" >  
                                <option value="">Selccionar Pronombre</option>
                                @foreach($Grupos as $i)
                                    <option value="{{$i->gpo}}">{{$i->gpo}}</option>
                                @endforeach
                                <option value="NUEVO">Otro</option>
                            </select>
                            @error('gpo')<error>{{$message}}</error> @enderror 
                        </div>

                        @if($gpo=='NUEVO')
                            <div class="form-group col-md-6 col-sm-12"> 
                                <label>Nuevo Pronombre: <red>*</red> </label>
                                <input type="text" class="form-control @error('gpo2')error @enderror" wire:model="gpo2"  value="{{old('gpo2')}}" >
                                @error('gpo2') <error>{{$message}} </error>@enderror
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <!-- ------------------------------- Nombre --------------------------- -->
                        <div class="form-group col-md-12 col-sm-12">
                            <label>Nombre: <red>*</red></label>
                            <input type="text" class="form-control @error('nombre')error @enderror" wire:model="nombre"  value="{{old('nombre')}}" >
                            @error('nombre') <error>{{$message}} </error>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- ------------------------------- sabores --------------------------- -->
                        <div class="form-group col-md-12 col-sm-12">
                            <label>Sabores: </label>
                            <input type="text" class="form-control @error('variantes')error @enderror" wire:model.defer="variantes"  value="{{old('variantes')}}" placeholder="chocolate,coco,guanábana">
                            <ch>Indicar sabores separados por comas.</ch>
                            @error('variantes') <error>{{$message}} </error>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- ------------------------------- Presentación --------------------------- -->
                        <div class="form-group col-md-12 col-sm-12">
                            <label>Presentación: <red>*</red></label>
                            <input type="text" class="form-control @error('presentacion')error @enderror" wire:model.defer="presentacion"  value="{{old('presentacion')}}" placeholder="Fco 500 ml">
                            <ch>Indicar sabores separados por comas.</ch>
                            @error('presentacion') <error>{{$message}} </error>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- ------------------------------- Entrega --------------------------- -->
                        <div class="form-group col-md-6 col-sm-12">
                            <label>Entrega:  <red>*</red></label>
                            <select class="form-control  @error('entrega')error @enderror"  wire:model="entrega" value="{{old('entrega')}}" >  
                                <option value="">Selccionar Entrega</option>
                                <option value="com1">Solo en Com1</option>
                                <option value="com2">Solo en Com2</option>
                                <option value="com12">En 1 y 2 independientes</option>
                                <option value="comid">En 1 y 2 idénticas</option>
                                <option value="no">No en entrega</option>
                                <option value="oca">Producto de Ocasión</option>
                            </select>
                            @error('entrega')<error>{{$message}}</error> @enderror 
                        </div>

                        <!-- ------------------------------- Venta en tienda --------------------------- -->
                        <div class="form-group col-md-6 col-sm-12">
                            <label>Venta en tienda:  <red>*</red></label>
                            <select class="form-control @error('venta')error @enderror"  wire:model="venta" value="{{old('venta')}}" >  
                                <option value="">Selccionar</option>
                                <option value="no">No en venta</option>
                                <option value="si">Si en venta</option>
                            </select>
                            @error('venta')<error>{{$message}}</error> @enderror 
                        </div>
                    </div>

                    
                    <div class="row">
                        <!-- ------------------------------- Costo --------------------------- -->
                        <div class="form-group col-md-6 col-sm-6">
                            <label>$ Costo: <red>*</red></label>
                            <input type="number" class="form-control @error('costo')error @enderror" wire:model="costo"  value="{{old('costo')}}" min='0'>
                            @error('costo') <error>{{$message}} </error>@enderror
                        </div>

                        <!-- ------------------------------- Precio act --------------------------- -->
                        <div class="form-group col-md-6 col-sm-6">
                            <label>$ Precio Admin: <red>*</red> </label>
                            <input type="number" class="form-control @error('precioact')error @enderror" wire:model="precioact"  value="{{old('precioact')}}" min={{$costo}}>
                            @error('precioact') <error>{{$message}} </error>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- ------------------------------- Precio Regis --------------------------- -->
                        <div class="form-group col-md-6 col-sm-6">
                            <label>$ Precio Cooperat: <red>*</red> </label>
                            <input type="number" class="form-control @error('precioreg')error @enderror" wire:model="precioreg"  value="{{old('precioreg')}}" min={{$costo}}>
                            @error('precioreg') <error>{{$message}} </error>@enderror
                        </div>

                        <!-- ------------------------------- Precio Publico --------------------------- -->
                        <div class="form-group col-md-6 col-sm-6">
                            <label>$ Precio Público: <red>*</red></label>
                            <input type="number" class="form-control @error('preciopub')error @enderror" wire:model="preciopub"  value="{{old('preciopub')}}" min={{$costo}}>
                            @error('preciopub') <error>{{$message}} </error>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- ------------------------------- Tipo de Mínimo --------------------------- -->
                        <div class="form-group col-md-6 col-sm-6">
                            <label>Mínimo de entrega?:  <red>*</red></label>
                            <select class="form-control @error('mintipo')error @enderror" wire:model="mintipo" value="{{old('mintipo')}}" >  
                                <option value=''>Indicar</option>
                                <option value='0'>No</option>
                                <option value='1'>Sí, x producto</option>
                                <!--option value="2">Dinero</option-->
                            </select>
                            @error('mintipo')<error>{{$message}}</error> @enderror 
                        </div>

                        <!-- ------------------------------- Valor Mínimo --------------------------- -->
                        @if($mintipo == '1')
                            <div class="form-group col-md-6 col-sm-6">
                                <label>Valor mínimo: </label>
                                <input type="number" class="form-control @error('min')error @enderror" wire:model="min"  value="{{old('min')}}" min='0'>
                                @error('min') <error>{{$message}} </error>@enderror
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <!-- ------------------------------- Descripción del producto --------------------------- -->
                        <div class="form-group col-md-12 col-sm-12">
                            <label>Descripcion: <red>*</red></label>
                            <textarea class="form-control @error('descripcion')error @enderror" wire:model="descripcion"  value="{{old('descripcion')}}" rows='5'></textarea>
                            @error('descripcion') <error>{{$message}} </error>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- ------------------------------- Responsable --------------------------- -->
                        <div class="form-group col-md-6 col-sm-12">
                            <label>Responsable:  <red>*</red></label>
                            <select class="form-control @error('responsable')error @enderror" " wire:model="responsable" value="{{old('responsable')}}" >  
                                <option value="">Selccionar Responsable</option>
                                @foreach ($responsables as $i)
                                    <option value="{{$i->usr}}">{{$i->nombre}} {{$i->ap_pat}}</option> 
                                @endforeach
                            </select>
                            @error('responsable')<error>{{$message}}</error> @enderror 
                        </div>

                        <!-- ------------------------------- Categoria --------------------------- -->
                        <div class="form-group col-md-6 col-sm-12">
                            <label>Categoría:  <red>*</red></label>
                            <select class="form-control @error('categoria')error @enderror" " wire:model="categoria" value="{{old('categoria')}}" >  
                                <option value="">Selccionar Categoria</option>
                                @foreach ($categorias as $i)
                                    <option value="{{$i->categoria}}">{{$i->categoria}}</option> 
                                @endforeach
                            </select>
                            @error('categoria')<error>{{$message}}</error> @enderror 
                        </div>
                    </div>
                    
                    <!-- ------------------------------- Imágen --------------------------- -->
                    <div class="row">
                        @if($img == '' || is_null($img))
                            <div class="col-md-6">
                                <label  class="form-label">Cargar Imágen</label>
                                <input class="form-control" type="file" wire:model="SubeImagen" accept="image/png, image/jpeg" >
                                @error('SubeImagen') <error>{{$message}}</error> @enderror
                                <div wire:loading wire:target="SubeImagen">Cargando archivo....</div>
                            </div>
        
                            @if($SubeImagen)                                
                                <div class="col-md-12">
                                    Previsualización de {{ $SubeImagen->getMimeType() }} :<br>
                                    <img src="{{$SubeImagen->temporaryUrl()}}" style="width:200px; border=1px solid black;">
                                </div>                                
                            @endif
                        @else
                            <div class="col-md-12">                                     
                                <img src={{asset("$img")}} style="width:200px; margin:15px;"><br>
                                <button type="button" class="btn btn-light"  style="width:200px; margin:15px;" wire:click="BorrrarImg({{$prodid}})"><i class="fa fa-close"></i> Borrar Imágen</button>
                            </div>
                        @endif
                    </div>  
                </div>

                <!-- ---------------------------------------------------------------------- Pie de modal de prucuctos  -------------------------------------------------------- -->
                <div class="modal-footer"> 
                    <div class="row" style="padding: 1rem; ">
                        <div class="col-md-4">
                            <button type="reset" class="btn btn-secondary"  data-dismiss="modal" style="margin:5px;"><i class="fa fa-close"></i> Cerrar</button></a>
                        </div>
                        <div class="col-md-4">
                            @if($text1=='Nuevo')
                                <button type="button" id='CierraModal1' wire:click="GuardaEdita('00')"  class="btn btn-success" style="margin:5px;"><i class="fas fa-plus"></i> Agregar producto</button>
                            @elseif($text1=="Editar")
                                <button type="button" id='CierraModal2' wire:click="GuardaEdita('{{$prodid}}')" class="btn btn-success" style="margin:5px;"><i class="fas fa-plus"></i> Editar evento {{$prodid}}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- ######################################################################################################################################################-->
    <!-- ######################################################################################################################################################-->
    <!-- ############################################# MODAL PARA PRODUCTOS DE OCASIÓN  #######################################################################-->
    <!-- ######################################################################################################################################################-->
    <!-- ######################################################################################################################################################-->
    <div class="modal fade " id="PedidoOcasion" tabindex="-1"  wire:ignore.self >
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(51, 51, 173);color:white;">
                    <div class="row container" >
                        <H1> Pedir producto de ocasión</H1>
                    </div>
                </div>                
                <div class="modal-body">
                    @if(count($ocasion)==0) ! Lo sentimos ¡ <br>  No hay ningún producto de ocasión para ofrecer  <br>@endif
                    @if(session('EnPedido')=='1')
                        <span style="color:red;">Atención</span>: Los pedidos de ocasión son independientes de los prepedidos.<br><br> 
                    @endif

                    @foreach($ocasion as $i)
                        <?php
                            #### Genera vector de sabores   
                            $sabores=explode(',', $i->variantes);
                        ?>
                        @foreach($sabores as $sabor)
                            <div style="row" style="border:1px solid black;">
                                <!-- ------------------------------- Producto de ocasión --------------------------- -->
                                <div class="form-group col-md-12 col-sm-12 col-xs-12" style="border-bottom:1px solid rgb(51, 51, 173)";>
                                    <label>
                                        {{$i->nombre}} {{$sabor}}:  $ 
                                        @if(auth()->user()->estatus == 'act') {{$i->precioact}} 
                                        @elseif(auth()->user()->estatus == 'reg') {{$i->precioreg}}
                                        @else {{$i->preciopub}}
                                        @endif
                                    </label>
                                    <div style="  text-align:right; float: right;">
                                        <input type="number" class="form-control" style="width:80px; display:inline-block;" id="" name="oca_{{$i->id}}@-{{$sabor}}">
                                    </div>
                                    <br>
                                    {{$i->descripcion}}<br>
                                    {{$i->presentacion}}. <br>
                                    Productor: {{$i->proveedor}}
                                    @if($i->img != '')
                                        <img src="" style="width:30%;">
                                    @endif
                                    <hr class="hr" style="width:80%;">
                                </div>
                            </div>
                        @endforeach
                    @endforeach

                    <!-- ------------------------------------ Pie de modal de productos de ocasión  ---------------------------- -->
                    <div class="modal-footer"> 
                        <div class="row" style="padding: 1rem; ">
                            <div class="col-md-12">
                                <button type="reset" class="btn btn-secondary"  data-dismiss="modal" style="margin:5px;"><i class="fa fa-close"></i>Cancelar</button></a>
                                <button type="submit" id='CierraModal3'  class="btn btn-success" style="margin:5px;"><i class="fas fa-plus"></i> Pedir</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>
                        
    

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
