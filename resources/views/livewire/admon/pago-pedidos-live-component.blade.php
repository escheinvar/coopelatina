<div>
    <h1>Validar pagos a partir de {{session('arrayMeses')[$mes]}}</h1>
    <div>
        @if(session('EnPedido')=='1')
            Estamos en período de pedidos, los cooperativistas todavía pueden subir sus pagos
        @else
            Ya finalizó el período de pedidos, los cooperativistas ya no pueden subir pagos
        @endif
    </div>
    <div>
        Hay {{$est['preps'] + $est['peds']}} registros: {{$est['peds']}} pedidos y {{$est['preps']}} prepedidos ({{$est['prepsSinPago']}} sin comprobante de pago)
        @if( $est['prepsSinPago'] > 0)
            <span style="color:red;">
                Faltan subir {{$est['prepsSinPago']}} comprobantes de pago
            </span><br>
        @endif
        @if($est['preps'] > 0)
            <span style="color:red;">
                Faltan validar {{$est['preps']}} prepedidos
            </span>  <br>    
        @endif
        @if($est['prepsSinPago']=='0' && $est['preps']=='0')
            <span style="color:orange;">
                Todo en orden
            </span>
        @endif
    </div>

    <!-- ----------------------- Estado ------------------------ -->
    @if( in_array(auth()->user()->priv, $petitCmte) )
        <div>
            <label> Estado</label><br>
            <label class="switch">
                <input type="checkbox" wire:model="EnPagos" >
                <div class="slider round"></div>
            </label>
            @if($EnPagos==false)
                <span style="color:gray;">Pagos inactivos. Abasto activo.</span>
            @else
            <span style="color:darkgreen;">Estamos en pagos. Abasto desactivado</span>
            @endif
        </div>
    @endif
    
    <br>
   
    <?php
    $mandaAbajo=[];
    ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <div class="col-md-1" style="display:inline-block;">
                        Folio
                    </div>
                    <div style="display:inline-block;width:350px;">
                        Nombre
                    </div>
                    <div style="display:inline-block;width:300px;">
                P        Estado
                    </div>

                    <div style="display:inline-block;">
                        Pago
                    </div>
                </th>
            </tr>
        </thead>

        <tbody>
            @if( in_array(auth()->user()->priv, $petitCmte) && $EnPagos==true)
                <div style="display:inline-block;" wire:model="destinoLanas"> 
                    <label>Indica el destino de la transacción:</label> 
                    <select  class="form-control">
                        <option value="">Indica el destino del dinero</option>
                        <option value="banco">a banco</option>
                        <option value="teso">a teso</option>
                        <option value="caja">a caja</option>
                    </select>
                </div>  
            @endif
        @foreach($pedidos as $p)
            <tr>
                <td>
                    <!-- ---------- ID ---------- -->
                    <div style="display:inline-block;">
                        {{sprintf('%04d', $p->fol_id)}}
                    </div>

                    <!-- ---------- NOMBRE ---------- -->
                    <div style="display:inline-block; @if($p->fol_edo == '0')text-decoration:line-through; @endif; width:350px;"> 
                        {{$p->nombre}} {{$p->ap_pat}} {{$p->ap_mat}}
                    </div>
                    
                    <!-- ---------- MES Y ESTADO ---------- -->
                    <div style="display:inline-block; width:300px;"> 
                        {{session('arrayMes')[$p->fol_mes]}}
                        @if($p->fol_edo =='5')
                            Pre pedido <b style="color:red;">con anualidad</b>

                        @elseif($p->fol_edo =='4' )
                            Pre pedido

                        @elseif($p->fol_edo == '3' )
                            Pedido
                        @elseif($p->fol_edo == '2' )
                            Pedido parcialmente entregado

                        @elseif($p->fol_edo == '1' )
                            Pedido entregado
                            
                        @elseif($p->fol_edo == '0' )
                            Pedido cancelado

                        @endif
                    </div>

                    <!-- ---------- Estado de Pago ---------- -->
                    <div style="display:inline-block; width:190px; @if($p->fol_edo == '0')text-decoration:line-through; @endif"> 
                        @if($p->fol_pagoimg != '')
                            <span style="cursor:pointer;" onclick="VerNoVer('imagenPago','{{$p->fol_id}}')"><i class="bi bi-file-image"></i> Ver Comprobante</span>
                        @else
                            <span style="color:gray">No comprobante</span>
                        @endif
                    </div>
                    <!-- ---------- Monto a pagar ---------- -->
                    <div style="display:inline-block; width:100px; @if($p->fol_edo == '0')text-decoration:line-through; @endif"> 
                        <?php 
                            $cont=0; $total=0;
                            foreach($prods as $prod) {
                                if($prod->ped_folio == $p->fol_id){
                                    $cont=$cont+$prod->ped_cant; 
                                    $subt= $prod->ped_cant * $prod->ped_costo; 
                                    $total=$total+ $subt; 
                                }
                            }
                            if($p->fol_pagoimg == '' && $p->fol_edo > 0) {
                                $mandaAbajo[]=['id'=>$p->fol_id, 'nombre'=>$p->nombre.$p->ap_pat, 'mes'=>$p->fol_mes, 'tot'=>$total];
                            }
                        ?>
                        $ {{$total}}
                    </div>

                    <!-- ---------- acciones: validar, rechazar o regresar ---------- -->
                    <div style="display:inline-block; "> 
                        @if( in_array(auth()->user()->priv, $petitCmte)   && $EnPagos==true)
                            @if($p->fol_edo >= '4') <!-- 4= pre pedido, 5= prepedido con anualidad -->
                                @if($destinoLanas !='') 
                                    <button class="btn btn-success" type="button" style="margin:0.5rem;" wire:click="AceptarPrepedido('{{$p->fol_id}}','{{$total}}','{{$p->fol_usrid}}','{{$p->fol_edo}}')"><i class="bi bi-hand-thumbs-up-fill"></i> Valida en {{$destinoLanas}} @if($p->fol_pagoimg =='')sin comprobante @endif </button> 
                                @endif
                                <button class="btn btn-danger" type="button" wire:click="RechazarPrepedido('{{$p->fol_id}}')"><i class="bi bi-hand-thumbs-down-fill"></i> Rechazar Pre pedido </button>
                            @elseif($p->fol_edo == '3' )<!-- 3=Pedido -->
                                <button class="btn btn-warning"  type="button" wire:click="RegresarAprepedido('{{$p->fol_id}}','{{$total}}')"> Regresar a pre pedido <i class="bi bi-exclamation-octagon"></i></button>
                            @elseif($p->fol_edo == '0' )<!-- 3=Cancelado -->
                                <button class="btn btn-secondary"  type="button" wire:click="DesRechazar('{{$p->fol_id}}')"> Des rechazar <i class="bi bi-exclamation-octagon"></i></button>
                            @endif
                        @endif
                    </div>

                    <!-- ---------- MUESTRA IMAGEN ---------- -->
                    <div id="sale_imagenPago{{$p->fol_id}}" style="display:none; width:100%;"> 
                        <a href="{{$p->fol_pagoimg}}" target="new"><img src="{{$p->fol_pagoimg}}" style="width:300px;"></a><br><br>
                        @if( in_array(auth()->user()->priv, $petitCmte) && $p->fol_edo >= '4'  && $EnPagos==true)
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmacion{{$p->fol_id}}">  <i class="bi bi-trash"></i> Borrar comprobante</button>
                        @endif
                        <!-- --------------------------- Modal de confirmación de borrado de imagen ---------------------- -->
                        <div class="modal fade" id="confirmacion{{$p->fol_id}}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Estás por <b>BORRAR</b> el comprobante de {{$p->nombre}} {{$p->ap_pat}}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Estamos seguros?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">NO. Ya me arrepentí</button>
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" wire:click="borrarimg({{$p->fol_id}})">Sí, borra el comprobante {{$p->fol_id}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
    
    <!------------------------------------------ sube nuevos comprobantes --------------------------------------------------- -->
    <div class="row" id="sale_pagarA" style="display:block; padding:5px;">
        @if(count($mandaAbajo)>0  && in_array(auth()->user()->priv, $petitCmte) && $EnPagos==true) 
            <form wire:submit.prevent="SubirPago"> <!-- prevent --> 
                @csrf
                <div class="mb-1">
                    <div class="col-md-4">
                        <label class="form-label">Subir imagen de comprobante de pago:</label>
                        <select class="form-control" wire:model="SubeComprTipo">
                            <option value="">Indica el pedido</option>
                            @foreach($mandaAbajo as $i)
                                <option value="{{$i['id']}}"> Id {{sprintf('%04d', $i['id'])}} &#9745; de {{session('arrayMes')[$i['mes']]}} &#9745; {{$i['nombre']}} por ${{$i['tot']}} </option>
                            @endforeach
                        </select>
                        @error('SubeComprTipo')<error>{{$message}}</error>@enderror

                    </div>
                    <div class="col-md-6">
                        <label for="SubeComprobPago" class="form-label">Sube el comprobante de pago</label>
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
    <br>
    Nota:
    <br><small><small>
    @if(in_array(auth()->user()->priv, $petitCmte) && $EnPagos==true)
        Al validar el pago, se generará el ingreso a la caja correspondiente<br>
        Al regresar a pre-pedido,  se genera la cancelación del pago<br>
        Al validar el pago CON ANUALIDAD, además se actualiza la anualidad del Cooperativista por un año<br>
        Al regresar a pre-pedido con ANUALIDAD, se genera la cancelación del pago, pero NO la de la ANUALIDAD<BR>
    @else
        Esta sección es administrada por el Tesorer@
    @endif
    </small></small>
</div>
