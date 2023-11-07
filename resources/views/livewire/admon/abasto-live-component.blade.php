<div>
   
    @if($proxima=='com1')
        <h1>Recepción de proovedores en primer entrega de {{session('arrayMeses')[$proximaDate['mes']]}}</h1>
    @else
        <h1>Recepción de proovedores en segunda entrega de {{session('arrayMeses')[$proximaDate['mes']]}}</h1>
    @endif
    @include('plantillas.MarcadorDeEstado')




    @if(session('ListasAbasto') == '' AND session('EnPagos') == '' AND session('EnPedido') =='0')
        <!-- -------------------- Cabecera de tabla -------------------- -->
        <div class="row sticky-top" style="font-size:110%; font-weight:bold;background-color:white;">
            <div class="col-md-5 col-sm-12 my-md-1 my-sm-12"> Producto a recibir </div>
            <div class="col-md-2 col-sm-12 my-md-12 my-sm-12"> Solicitud<br>pedidos+tienda</div>
            <div class="col-md-1 col-sm-12 my-md-1 my-sm-12"> Unidades recibidas </div>
            <div class="col-md-2 col-sm-12 my-md-1 my-sm-12"> Total a pagar<br> <span style="color:gray;">Plan</span> | Real</div>
            <div class="col-md-2 col-sm-12 my-md-1 my-sm-12"> 
                <button type="submit" class="btn btn-success">Recibir y pagar</button> 
                @if(in_array(auth()->user()->priv, $petitCmte) )
                <select name="FuenteDelPago" >
                    <option value="caja" selected>Caja</option>
                    <option value="teso">Teso</option>
                    <option value="banco">Banco</option>
                </select>
                @endif                
            </div>
        </div>
        <!-- -------------------- Incia tabla de -------------------- -->
        <form method="post">
            @csrf
            <?php $GranTotalPedido='0'; $GranTotalReal='0';?>
            @foreach($proveedores as $prov)
                <?php $idProv=preg_replace("/ /","",$prov->proveedor); ?>
                <div id="CintaProv_{{$idProv}}">
                    <!-- #########################################################################################################################################-->
                    <!-- ###############################################  CABEZA VISIBLE #########################################################################-->
                    <div id="CabezaProv_{{$idProv}}" style="background-color:rgb(186, 214, 240); margin:1rem; font-size:120%; font-weight:bold;cursor:pointer; padding:1rem;" onclick="VerNoVer('prov','{{$idProv}}')"> 
                        {{$prov->proveedor}} ({{$idProv}})
                    </div>
                    <!-- #########################################################################################################################################-->
                    <!-- ###############################################  CUERPO OCULTO  #########################################################################-->
                    <div id="sale_prov{{$idProv}}" style="display:block;">
                        <?php $subtotDelProov='0'; $subtotRealDelProov='0';?>
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
                                    <div class="row my-sm-5"  style="margin:2rem;"> 
                                        <div style="">
                                            
                                            <!--################################ NOMBRE PRODUCTO #######################################-->
                                            <div class="col-md-5 col-sm-12 my-md-1 my-sm-3"> 
                                                <b>{{$i->gpo}} {{$i->nombre}}</b>  {{$sabor}} {{$i->presentacion}}  (${{$i->costo}})
                                                <br>{{$i->ped_producto}}
                                            </div>
                                            <!--############################# CANTIDADES SOLICITADAS ####################################-->
                                            <div class="col-md-2 col-sm-12 p-md-1 p-sm-7" >
                                                <span style="font-size:120%"><ch>Peds</ch>{{$i->total - $tienda}}</span> + 
                                                <span style="font-size:120%"><ch>Tien</ch>{{$tienda}}</span> = 
                                                <span style="font-size:120%;font-weight:bold;"><ch>Total</ch> {{$i->total}} </span>
                                            </div>
                                            <!--############################# CANTIDADES ENTREGADAS ####################################-->
                                            <div class="col-md-1 col-sm-12 my-md-1 my-sm-12" style="text-align: center;">
                                                @if($i->aba_abasto=='0')
                                                    <input class='producto' id="{{$i->ped_producto}}" 
                                                        name="{{$i->ped_producto}}" type="number" style="width:70px;"  min="0"
                                                        value="{{ old($i->ped_producto) }}"
                                                        onchange="CalculaSubtotal({{$i->costo}},'{{$i->ped_producto}}',{{$i->total - $tienda}});calculaProv('{{$idProv}}');" 
                                                        min='0'> 
                                                @else                                                    
                                                    <span style="@if($i->aba_faltante=='1')color:red; @endif">  {{$i->aba_abasto_cant}} </span>
                                                @endif
                                            </div>
                                            <!--############################# SUBTOTAL DE CADA PRODUCTO ################################-->
                                            <div class="col-md-2 col-sm-12 my-md-1 my-sm-12" style="display:flex;">
                                                <?php $subtotDelProov= $subtotDelProov + ($i->costo * $i->total); ?>
                                                <div style="width:40%;color:gray;font-size:80%;">$ {{$i->costo * $i->total}}</div>
                                                @if($i->aba_abasto =='0')
                                                    $ <div style="width:60%;" class="totalProductor_{{$idProv}}" id="totProd_{{$i->ped_producto}}"></div>
                                                @else
                                                    <?php $subtotRealDelProov = $subtotRealDelProov + ($i->aba_abasto_cant * $i->costo); ?>
                                                    $ {{$i->aba_abasto_cant * $i->costo}}
                                                @endif
                                            </div>
                                            <!--############################# COLUMNA DE AVISOS ################################-->
                                            <div class="col-md-2 col-sm-12 my-md-1 my-sm-12">
                                                @if($i->aba_abasto > '0')
                                                    @if($i->aba_faltante=='1')<error>Faltó para la entrega</error> @endif
                                                @endif
                                                <div><error><span id="totAviso_{{$i->ped_producto}}"></span></error></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                        <!-- -------------------------- pie de tabla ------------------------- -->
                        <div class="row" style="text-size:110%; font-weight:bold; background-color:aliceblue;">
                            <div class="col-md-5 col-sm-12 my-md-1 my-sm-12"> 
                                &nbsp;
                            </div>
                            <div class="col-md-2 col-sm-12 my-md-12 my-sm-12">
                                Total =
                            </div>
                            <div class="col-md-1 col-sm-12 my-md-1 my-sm-12" style="text-align: center;">
                                $<span class="TOTALProductor" id="totalProductor_{{$idProv}}"></span>
                            </div>
                            <div class="col-md-2 col-sm-12 my-md-1 my-sm-12" style="display:flex;">
                                <?php $GranTotalPedido=$GranTotalPedido+$subtotDelProov; $GranTotalReal=$GranTotalReal+$subtotRealDelProov?>
                                <div style="width:40%;">$ {{$subtotDelProov}}</div>
                                <div style="width:60%;" >$ {{$subtotRealDelProov}}</div>
                            </div>
                            <div class="col-md-2 col-sm-12 my-md-1 my-sm-12">
                                <!--button type="button" class="btn btn-success btn-sm">Pagar al proveedor</button-->
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <!--- ------------------------- Boton y cálculo de gran total -------------------------- -->
            <hr class="border border-2 opacity-75" style="width:90%">
            <div class="row" style="text-size:120%; font-weight:bold;">
                Gran total calculado: $     {{$GranTotalPedido}}<br>
                Gran total pagado: $ {{$GranTotalReal}}
            </div>
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

                if(cantidad < CantEnPedido){ Aviso = 'Faltará entregar a '+(CantEnPedido - cantidad)+' Cooperativistas';
                }else{                      Aviso = '';      }
                document.getElementById('totAviso_'+producto).innerHTML = Aviso                
                //document.getElementById('recibe_'+producto).style.display = "block"
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
                document.getElementById('totalProductor_'+proveedor).innerHTML = tot;
                console.log('a',tot,typeof(total))
           }
            //--------------------------------  Calcula gran total de todos los proveedores
            function calculaGranTotalBAC(){
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
