<div>
    <div class="col-md-12 tab-pane active" id="activos" >
        @if($GranVariable=='activos') <h1>Mis Pedidos Activos</h1> @elseif($GranVariable == 'inactivos')<h1>Mis Pedidos históricos</h1> @endif
    </div>

    <input type="radio" wire:model="GranVariable" value="activos" >
    <label for="html">Pedidos Activos</label><br>

    <input type="radio" wire:model="GranVariable" value="inactivos" >
    <label for="css">Pedidos Históricos</label><br>
    
    <?php
        $mandaAbajo=[];
    ?>
    @if($GranVariable=='activos')
        @if(session('EnPedido')=='1')
            <h3>Estamos tomando pedidos, no olvides subir tu comprobante de pago</h3>
        @else
            <h3>Finalizó la toma de pedidos, ya no puedes subir tu pago.</h3>
        @endif
    @endif
   
    @foreach($folios as $folio)
        <div class="col-lg-12 col-md-12 col-sm-12" style="margin:15px; background-color:aliceblue; padding:2rem; ">
            <?php 
                if($folio->fol_edo >='4'){
                    $col="rgb(225, 234, 244)";
                    if(session('EnPedido')=='1'){
                        $title="Pre-pedido por pagar para ".session('arrayMeses')[$folio->fol_mes];
                    }else{
                        $title="Pre-pedido no pagado de ".session('arrayMeses')[$folio->fol_mes];
                    }
                }else if($folio->fol_edo =='3'){
                    $col="rgb(193, 210, 225)";
                    $title="Pedido  para ".session('arrayMeses')[$folio->fol_mes];                
                }else if($folio->fol_edo =='2'){
                    $col="rgb(193, 210, 225)";
                    $title="Pedido en entrega";                
                }else if($folio->fol_edo =='1'){
                    $col="gray";
                    $title="Pedido entregado";                
                }else if($folio->fol_edo =='0'){
                    $col="gray  ";
                    $title="Pedido cancelado";                
                }
            ?>
       
            <!-- ---------------------- Cintillo de cabecera con nombre de estado de pedido --- ------------------------ -->
            <div style="background-color:{{$col}}; padding:7px; border-radius:5px;" >
                <i class="bi bi-bag-plus-fill"></i><span style="@if($folio->fol_edo == '0')text-decoration:line-through; @endif">
                {{sprintf('%04d', $folio->fol_id)}}</span> {{$title}} 
                @if($folio->fol_edo == '5') <span style="padding:1rem; color:red; padding:5px;border:1px solid rgb(230, 233, 253);border-radius:14px;"><b>Anualidad</b> @endif </span>
                
            </div>

            <!-- ------------------------- Segunda línea con ID y con estado, ver o subir foto y borrado ------------------------------ -->
            <div style="background-color:{{$col}};padding:1 rem;"> 
                @if($folio->fol_edo >='4')
                    <!-- ----------------------------- celda de estado de pago ----------------------- -->           
                    <div class="col-lg-3 col-md-3 col-sm-12" style="display:inline-block;">
                        @if( is_null($folio->fol_pagoimg)|| $folio->fol_pagoimg=='')
                            @if(session('EnPedido')==1)
                                <i class="bi bi-camera-fill"></i> <span class="d-none d-sm-inline"> Esperando comprobante </span>
                            @else
                                <i class="bi bi-x-circle"></i> <span class="d-none d-sm-inline"> No fué pagado</span> 
                            @endif
                        @else 
                            <span onclick="VerNoVer('pagoimg',{{$folio->fol_id}})" style="cursor:pointer;">
                                <i class="bi bi-card-image"></i> <span class="d-none d-sm-inline" > Ver comprobante</span> 
                            </span>
                        @endif  
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-12" style="display:inline-block;" >
                        @if(is_null($folio->fol_pagoimg) || $folio->fol_pagoimg == '')
                            <span style="cursor:pointer;" data-toggle="modal" data-target="#confirmacion{{$folio->fol_id}}">
                                <i class="bi bi-trash"></i> <span class="d-none d-sm-inline"> Cancelar</span>
                            </span>
                        @else
                            <div class="col-lg-3 col-md-2 col-sm-12" style="display:inline-block;" > &nbsp; </div>
                        @endif  
                    </div> 
                @else
                    @if( !is_null($folio->fol_pagoimg)|| !$folio->fol_pagoimg=='')
                        <span onclick="VerNoVer('pagoimg',{{$folio->fol_id}})" style="cursor:pointer;">
                            <i class="bi bi-card-image"></i> <span class="d-none d-sm-inline" > Ver comprobante</span> 
                        </span>
                    @else
                        <div class="col-lg-5 col-md-5 col-sm-0"> &nbsp; </div>
                    @endif
                @endif
           
                
                <div class="col-lg-3 col-md-3 col-sm-12" style="display:inline-block;"> 
                    <span onclick="VerNoVer('com1','{{$folio->fol_id}}');VerColor('com1Boton',{{$folio->fol_id}});" id="com1Boton{{$folio->fol_id}}" style="background-color:aliceblue; cursor:pointer; font-size:80%; margin:2rem; padding:.5rem; border:1px solid rgb(214, 219, 249); border-radius:5px; @if($folio->fol_edo == '0')text-decoration:line-through; @endif">Entrega1</span>
                    <span onclick="VerNoVer('com2','{{$folio->fol_id}}');VerColor('com2Boton',{{$folio->fol_id}});" id="com2Boton{{$folio->fol_id}}" style="background-color:aliceblue; cursor:pointer; font-size:80%; margin:2rem; padding:.5rem; border:1px solid rgb(230, 233, 253); border-radius:5px; @if($folio->fol_edo == '0')text-decoration:line-through; @endif">Entrega2</span>
                </div>
            </div>

            <!-- --------------------------- Modal de confirmación de borrado ---------------------- -->
            <div class="modal fade" id="confirmacion{{$folio->fol_id}}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Estás por <b>CANCELAR</b> tu pre pedido {{$folio->fol_id}}</h4>
                        </div>
                        <div class="modal-body">
                            <p>¿Continuamos con la cancelación?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">NO. Manten mi pre pedido</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" wire:click="borrarPedido({{$folio->fol_id}})">Sí, cancela y borra el pre pedido</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
        
   
            <!-- ------------------------ Inicia listado de productos  ----------------------------- -->
            <div id="sale_textOculto{{$folio->fol_id}}" style="display:block;">
                <?php $num1=0;$num2=0; $tot1=0; $tot2=0; ?>
                <!-- ------------------------- lista de com1 ------------------------ -->
                <div class="col-lg-6 col-md-6 col-sm-12" style="border:0px solid {{$col}}; display:none"  id="sale_com1{{$folio->fol_id}}" >
                    <div style="padding:2rem;"><b style="cursor:pointer; @if($folio->fol_edo == '0')text-decoration:line-through; @endif;" onclick="VerNoVer('com1','{{$folio->fol_id}}');VerColor('com1Boton',{{$folio->fol_id}});">Primer Entrega</b></div>
                    @foreach($prods as $prod)
                        <?php $subt=0; ?>
                        @if($prod->ped_folio == $folio->fol_id  && $prod->ped_entrega =='com1')
                        <?php       $num1=$num1 + $prod->ped_cant;      $subt = $prod->ped_cant * $prod->ped_costo;    $tot1= $tot1+$subt; ?>
                            <div style="border-bottom:1px solid {{$col}};"> 
                                @if($folio->fol_edo <= '3' && $folio->fol_edo <'0')<input type="checkbox" style="width:20px;height:20px;"> @else <span style="color:gray">&#9745;</span>    @endif
                                {{$prod->ped_cant}} {{$prod->ped_prod}} {{$prod->ped_prodvar}} <small style="color:gray;">({{$prod->ped_prodpresenta}} ${{$prod->ped_costo}})</small>
                                @if($folio->fol_edo >='4' &&  (is_null($folio->fol_pagoimg) || $folio->fol_pagoimg=='' ) ) &nbsp; <small style="color:gray;"><i class="bi bi-trash" style="cursor:pointer;" wire:click="borrarProducto({{$prod->ped_id}})"></i> </small>@endif
                                <span style="float: right;">${{$subt}}                                    
                            </div>
                        @endif
                    @endforeach
                    @if($num1 == '0') <br>- No pediste nada - <br> @endif
                    <b>Total: ${{$tot1}}</b> <br> 
                </div>
                <!-- ------------------------- lista de com2 ------------------------ -->
                <div class="col-lg-6 col-md-6 col-sm-12" style="border:0px solid {{$col}}; display:none;" id="sale_com2{{$folio->fol_id}}">
                    <div style="padding:2rem;"><b style="cursor:pointer;@if($folio->fol_edo == '0')text-decoration:line-through; @endif" onclick="VerNoVer('com2','{{$folio->fol_id}}');VerColor('com1Boton',{{$folio->fol_id}});">Segunda Entrega</b></div> 
                    @foreach($prods as $prod)
                        <?php $subt=0; ?>
                        @if($prod->ped_folio == $folio->fol_id && $prod->ped_entrega =='com2')
                            <?php       $num2=$num2 + $prod->ped_cant;      $subt = $prod->ped_cant * $prod->ped_costo;   $tot2= $tot2 + $subt; ?>
                            <div style="border-bottom:1px solid {{$col}};"> 
                                @if($folio->fol_edo <= '3' && $folio->fol_edo <'0'  )<input type="checkbox" style="width:20px;height:20px;"> @else <span style="color:gray">&#9745;</span> @endif
                                {{$prod->ped_cant}} {{$prod->ped_prod}} {{$prod->ped_prodvar}} <small style="color:gray;">({{$prod->ped_prodpresenta}} ${{$prod->ped_costo}})</small>
                                @if($folio->fol_edo >='4' &&  (is_null($folio->fol_pagoimg)|| $folio->fol_pagoimg=='' )) &nbsp; <small style="color:gray;"><i class="bi bi-trash" style="cursor:pointer;" wire:click="borrarProducto({{$prod->ped_id}})"></i> </small>@endif
                                <span style="float: right;">${{$subt}}
                            </div>
                        @endif
                    @endforeach
                    @if($num2 == '0') <br>- No pedista nada - <br> @endif
                    <b>Total: ${{$tot2}}</b> <br> <br>
                </div>
            </div> 
            
            <!-- --------------------------- Muestra imágen de comprobante de pago ---------------------- -->         
            <div class="col-lg-12 col-sm-12" id="sale_pagoimg{{$folio->fol_id}}" style="display:none; padding:5px;">
                Comprobante: <br>               
                <a href="{{$folio->fol_pagoimg}}" target="new"><img src="{{$folio->fol_pagoimg}}" style="width:300px;"></a><br><br>
                <span style="cursor: pointer;" wire:click="borrarimg({{$folio->fol_id}})"><i class="bi bi-trash"></i> Eliminar comprobante</span>
            </div>

            <!-- ------------------------ Barra Inferior de Total    ----------------------------- -->
            <div class="col-lg-12 col-md-12" style="background-color:{{$col}}; @if($folio->fol_edo == '0')text-decoration:line-through; @endif"  >
                <span>&#9312;<b>{{$num1}}</b> &nbsp;</span> <span>&#9313;<b>{{$num2}}</b>  prods.</span>  &nbsp;   <span><b>$&nbsp;{{$tot1+$tot2}}</b></span>
                <?php 
                    //-------------vars para enviar a Inicia subir comprobante
                    if($folio->fol_pagoimg == ''){$ConImg=0;}else{$ConImg=1;}
                    $mandaAbajo[]=['id'=>$folio->fol_id, 'mes'=>$folio->fol_mes, 'tot'=>$tot1+$tot2, 'edita'=>$ConImg];  
                ?>
            </div>
        </div>
    @endforeach

    <!-- ------------------------ Inicia Subir Comprobante    ----------------------------- -->
    <div class="row" id="sale_pagarA" style="display:block; padding:5px;">
        @if(count($mandaAbajo)>0 && session('EnPedido')==1 )
            <form wire:submit.prevent="SubirPago"> <!-- prevent --> 
                @csrf
                <div class="mb-1">
                    <div class="col-md-4">
                        <label class="form-label">Subir imagen de comprobante de pago:</label>
                        <select class="form-control" wire:model="SubeComprTipo">
                            <option value="">Indica el pedido</option>
                            @foreach($mandaAbajo as $i)
                                <option value="{{$i['id']}}_{{$i['mes']}}"> Id {{sprintf('%04d', $i['id'])}} &#9745; de {{session('arrayMes')[$i['mes']]}} por ${{$i['tot']}} @if($i['edita']=='1') Reemplazar @endif </option>
                            @endforeach
                        </select>
                        @error('SubeComprTipo')<error>{{$message}}</error>@enderror

                    </div>
                    <div class="col-md-6">
                        <label for="SubeComprobPago" class="form-label">Sube tu comprobante de pago</label>
                        <input class="form-control" type="file" id="SubeComprobPago" wire:model="SubeComprobPago">
                        @error('SubeComprobPago') <error>{{$message}}</error> @enderror
                        <div wire:loading wire:target="SubeComprobPago">Cargando archivo....</div>
                    </div>

                    @if($SubeComprobPago)
                        
                        <div class="col-md-12">
                            Previsualización de {{ $SubeComprobPago->getMimeType() }} :<br>
                            <img src="{{$SubeComprobPago->temporaryUrl()}}" style="width:200px; border=1px solid black;">
                        </div>
                    @endif

                    <div class="col-md-1">
                        <label>  </label>
                        <button class="btn btn-primary" type="submit" style="margin:10px;" id="botonsubir"><i class="bi bi-camera-fill"></i> Subir</button>
                    </div>
                   
                </div>
            </form>                   
        @endif  
    </div>
    

    @push('scripts')
        <script >           
            function VerColor(idcolor, idfolio){
                var name = idcolor+idfolio;
                var bla =  document.getElementById(name);
                
                if (bla.style.backgroundColor == "aliceblue"){
                    bla.style.backgroundColor = "rgb(229, 251, 240)";
                }else {
                    bla.style.backgroundColor="aliceblue";
                }
            }
        </script>
    @endpush

</div>
