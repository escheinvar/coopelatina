<div>
   
    @if($proxima=='com1')
        <h1>Recepción de proovedores en primer entrega de {{session('arrayMeses')[$proximaDate['mes']]}}</h1>
    @else
        <h1>Recepción de proovedores en segunda entrega de {{session('arrayMeses')[$proximaDate['mes']]}}</h1>
    @endif
    @include('plantillas.MarcadorDeEstado')




    @if(session('ListasAbasto') == '' AND session('EnPagos') == '' AND session('EnPedido') =='0')
        <!-- -------------------- Cabecera de tabla -------------------- -->
        <div class="row" style="font-size:120%; font-weight:bold;color:gray;">
            <div class="col-md-4"> Producto a entregar </div>
            <div class="col-md-2"> Solicitud<br>pedidos+tienda</div>
            <div class="col-md-1"> Unidades recibidas </div>
            <div class="col-md-2"> Total a pagar</div>
        </div>
        <!-- -------------------- Incia tabla de -------------------- -->
        <form method="post">
            @csrf
            @foreach($proveedores as $prov)
                <?php $idProv=preg_replace("/ /","",$prov->proveedor); ?>
                <div id="CintaProv_{{$idProv}}">
                    <!-- ------------------------------ Cabecera con nombre de proveedor ----------------------------------- -->
                    <div id="CabezaProv_{{$idProv}}" style="background-color:rgb(186, 214, 240); margin:1rem; font-size:120%; font-weight:bold;cursor:pointer; padding:1rem;" onclick="calculaProv('{{$idProv}}');calculaGranTotal();VerNoVer('prov','{{$idProv}}')"> 
                        Proveedor: {{$prov->proveedor}} ({{$idProv}})
                    </div>
                    <!-- ------------------------------- CUERPO oculto---------------------------------- -->
                    <div id="sale_prov{{$idProv}}" style="display:block;">
                        @foreach($productos as $i)
                            @if($prov->proveedor==$i->proveedor)
                                <?php 
                                    $tienda="0";
                                    $bla=preg_replace("/.*:/","",$i->ped_producto); 
                                    $productoID=preg_replace("/@.*/","",$bla);
                                    $sabor=preg_replace("/.*@/","",$bla);
                                    foreach ($productosTienda as $t) {
                                        if($i->ped_producto == $t->ped_producto){
                                            $tienda= $t->total;
                                        }
                                    }
                                ?>
                                @if($i->aba_listas=='1')
                                    <div class="row"  style="margin:2rem;"> 
                                        <div style="">
                                            <!-- --------------------------- NOMBRE PRODUCTO --------------------- -->
                                            <div class="col-md-5 col-sm-12 my-md-1 my-sm-5"> 
                                                <b>{{$i->gpo}} {{$i->nombre}}</b>  {{$sabor}} {{$i->presentacion}}  (${{$i->costo}})
                                                <br>{{$i->ped_producto}}
                                            </div>
                                            <!-- --------------------------- CANTIDADES --------------------- -->
                                            <div class="col-md-2 col-sm-12 my-md-1 my-sm-5">
                                                <span style="font-size:120%"><ch>Pedidos</ch>{{$i->total - $tienda}}</span> + 
                                                <span style="font-size:120%"><ch>Tienda</ch>{{$tienda}}</span> = 
                                                <span style="font-size:120%;font-weight:bold;"><ch>Total</ch> {{$i->total}} </span>
                                            </div>
                                            <!-- --------------------------- CANTIDAD ENTREGADA --------------------- -->
                                            <div class="col-md-1 col-sm-12 my-md-1 my-sm-5">
                                                @if($i->aba_abasto=='0')
                                                    <input class='producto' id="{{$i->ped_producto}}" name="{{$i->ped_producto}}" type="number" style="width:70px;"  onchange="CalculaSubtotal({{$i->costo}},'{{$i->ped_producto}}',{{$i->total - $tienda}});calculaProv('{{$idProv}}');calculaGranTotal()" min='0'> 
                                                @else
                                                    <span> {{$i->total}}</span>
                                                @endif
                                            </div>
                                            <!-- --------------------------- SUB TOTALPAGO --------------------- -->
                                            <div class="col-md-1 col-sm-12 my-md-1 my-sm-5">
                                                $ <span class="totalProductor_{{$idProv}}" id="totProd_{{$i->ped_producto}}">{{$i->costo * $i->total}}</span>
                                            </div>
                                            <!-- --------------------------- Acciones --------------------- -->
                                            <div class="col-md-3 col-sm-12 my-md-1 my-sm-5">
                                                @if($i->aba_abasto =='0')
                                                    <button type="submit" name="ganon" value='{{$i->ped_producto}}' id="recibe_{{$i->ped_producto}}" style="display:block;" class="btn btn-success" wire:click="Recibir('{{$i->ped_producto}}')">Recibir y pagar</button>
                                                    
                                                @endif
                                                <div><error><span id="totAviso_{{$i->ped_producto}}"></span></error></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                        <div class="row" style="margin:2rem;">
                            <div class="col-md-12 col-sm-12" style="font-size:120%;font-weight:bold;">
                                Pago total al proveedor: $ <span class="TOTALProductor" id="totalProductor_{{$idProv}}"></span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <!--- ------------------------- Boton y cálculo de gran total -------------------------- -->
            @if( in_array(auth()->user()->priv, $petitCmte) )
                <?php
                    $concatenado="";
                    foreach($proveedores as $i){
                        $idProv=preg_replace("/ /","",$i->proveedor); 
                        $concatenado=$concatenado."calculaProv('".$idProv."');";
                    }
                ?>                 
                <div class="row" style="margin:2rem; padding:2rem; font-size:130%; font-weight:bold;color:gray;">
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary" onclick="{{$concatenado}}calculaGranTotal();VerNoVer('Gran','Totalisimo')">Calcular Total</button>
                    </div>
                    <div id="sale_GranTotalisimo" class="col-md-6" style="display:none;">
                        Total a pagar a los proveedores: $ <span id="GranTotal"></span> 
                    </div>
                </div>
            @endif
        </form>
    @else
        Aún no es tiempo de recibir proveedores!!
    @endif
      
    @push('scripts')
        <script type="text/javascript">  
            //--------------------------------  Calcula subtotal  por cada producto
            function CalculaSubtotal(precio,producto,CantEnPedido){
                cantidad= document.getElementById(producto).value;
                total = parseFloat(cantidad) * parseFloat(precio);
                
                document.getElementById('totProd_'+producto).innerHTML = parseFloat(total);
                if(cantidad < CantEnPedido){
                    falta = CantEnPedido - cantidad
                    Aviso = 'Faltará entregar a '+falta+' Cooperativistas';
                }else{
                    Aviso = '';
                }
                document.getElementById('totAviso_'+producto).innerHTML = Aviso
                
                //document.getElementById('recibe_'+producto).style.display = 'none'
                //document.getElementById('guarda_'+producto).style.display = 'block'
                //console.log('a',total,CantEnPedido,cantidad, typeof(total),typeof(cantidad))
            }

            //--------------------------------  Calcula total por cada proveedor
            function calculaProv(proveedor){
                cadaUno=document.getElementsByClassName('totalProductor_'+proveedor);
                var x=0; var tot=0;
                for (i=0, max=cadaUno.length; i<max; i++) {
                    x = parseFloat(cadaUno[i].innerHTML);
                    if(isNaN(x) || x=="") {x=0;}
                    tot += parseFloat(x);
                }
                //console.log('a',tot,cadaUno)
                document.getElementById('totalProductor_'+proveedor).innerHTML = tot;
                //document.getElementById('CabezaProv_'+proveedor).style.backgroundColor = 'aliceblue';
           }
            //--------------------------------  Calcula gran total de todos los proveedores
            function calculaGranTotal(){
                cadaUno=document.getElementsByClassName('TOTALProductor');
                var x=0; var tot=0;  var contador=0;
                for (i=0, max=cadaUno.length; i<max; i++) {
                    x = parseFloat(cadaUno[i].innerHTML);
                    if(isNaN(x) || x=="") {x=0;}
                    tot += parseFloat(x);
                }
                // console.log('a',tot,)
                document.getElementById('GranTotal').innerHTML =tot;
            }
        </script>
    @endpush 

</div>
