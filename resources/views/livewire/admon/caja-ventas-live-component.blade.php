<div>
    <div class="conteiner-fluid">
        <h1>Caja Ventas</h1>

        <!--######################################################################################################################-->
        <!--######################################################################################################################-->
        <!--####################################### SELECTOR DE NOMBRE DE  COOPERATIVISTA  #######################################-->
        <!--######################################################################################################################-->
        <div class="row sticky-top" style="background-color: white; padding:1.5rem;">
            <div class="col-lg-3 col-md-3-col-sm-6">
                <div class="form-group" wire:ignore >
                    <div class="" >
                        <label>Cooperativista: </label>
                        <select class="select2 form-select" wire:model="idusr" name="usr">     
                            <option value="0">Anónimo</option>
                            @if(!empty($usuarios))
                                @foreach($usuarios as $i)
                                    <option value="{{$i->id}}">{{$i->nombre}} {{$i->ap_pat}} {{$i->ap_mat}}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('seleccionado')<error>{{$message}}</error>@enderror
                    </div>
                </div>
            </div>
        
            <div class="col-lg-2 col-md-2 col-sm-6">
                <button type="submit" class="btn btn-primary">Registrar caja</button><br>            
                <span style="font-size:110%;font-weight:bold">= $ <span id="GranGranTotal"></span></span>
                <input type="hidden" name="GranGranTotal" id="GranGranTotal2">
                @error('GranGranTotal')<error>{{$message}}</error>@enderror
            </div> 
        </div>

        <!--######################################################################################################################-->
        <!--######################################################################################################################-->
        <!--############################################# DEVOLVER PEDIDOS #####################################################-->
        <!--######################################################################################################################-->
        @if($this->idusr > '0')
            <div class="row">
                <div style="background-color: rgb(184, 219, 249); padding:1.5rem; margin:1rem; font-size:120%; cursor:pointer; width:80%; border-radius:0px 15px 15px 0px;" onclick="VerNoVer('devolver','pedidos')">
                    <i class='fas fa-hand-holding-usd'></i> Devolver Pedidos @if($idusr) de {{$usr->nombre}} @endif
                    $ <span id="totaldevuelve"> </span>
                </div>
            </div>

            <div class="row" id="sale_devolverpedidos" style="display:none;">
                @if(count($pedidos) > 0)
                    @foreach($pedidos as $p)
                        <?php $monto='0'; ?>
                        <div class="col-lg-6 col-md-6 col-sm-12 py-3">
                            <div class="form-check" >
                                <input type="checkbox"
                                    onclick="subtotal('{{$p->ped_id}}','devuelve','');"
                                    id='prod_{{$p->ped_id}}devuelve'
                                    class="subtotalDeDevoluciones form-check-input" 
                                    value="{{($p->ped_cant - $p->ped_cantentregada) * $p->ped_costo * -1 }}"
                                    name="devuelve_{{$p->ped_id}}"> 

                                <span style="font-size:110%;font-weight:bold">{{$p->ped_prod}} <span style="color:gray;">{{$p->ped_prodvar}}</span></span> 
                                <span style="font-size:90%;">{{$p->ped_prodpresenta}} </span> ${{$p->ped_costo}}
                                <span id="subtot_{{$p->ped_id}}devuelve" style="display:none;"> </span>
                                <div>
                                    Pidió {{$p->ped_cant}}, se le entregó {{$p->ped_cantentregada}} 
                                    ({{$p->ped_entrega}} de  {{session('arrayMeses')[$p->fol_mes]}}  {{$p->fol_anio}})
                                </div>
                                <div>
                                    <span style="font-weight:bold;">Se deben {{$p->ped_cant - $p->ped_cantentregada}} = $ {{($p->ped_cant - $p->ped_cantentregada) * $p->ped_costo }}</span>
                                    <span class="BotonesEspeciales" style="display:block-inline;"> &nbsp; &nbsp;
                                        <button type="button" wire:click="transfiere('{{$p->ped_id}}')" class="btn btn-success btn-xs"> Transferir</button>  &nbsp; 
                                    </span>
                                </div>
                                <div style="font-size:90%;color:gray;">
                                    @if($p->aba_abasto  = '1') El proveedor no llegó o canceló. @endif
                                </div>
                                <div style="font-size:90%;color:gray;">
                                    @if($p->aba_faltante = '1') No hubo suficiente producto (error de pedido o de proveedor). @endif
                                </div>
                                {{$p->ped_id}}){{$p->ped_producto}}
                            </div>
                        </div>
                    @endforeach
                @else
                    -No hay ningún producto marcado para devolver al cooperativista-
                @endif
            </div>
        @else
            <!-- requiere los campos para javascript (de lo contrario, busca sobtotalDeDevoluciones y totaldevuelve y no los encuentra) -->
            <input type="checkbox" id='prod_NULOdevuelve' class="subtotalDeDevoluciones"  value="0" name="devuelve_NULO" checked style="display:none;"> 
            <span id="subtot_NULOdevuelve" style="display:none;"></span>
            <span id="totaldevuelve" style="display:none;"></span>
        @endif

        <!--######################################################################################################################-->
        <!--######################################################################################################################-->
        <!--############################################# REGRESAR ENVASES #####################################################-->
        <!--######################################################################################################################-->
        <div>
            <div class="row">
                <div style="background-color: rgb(184, 219, 249); padding:1.5rem; margin:1rem; font-size:120%; cursor:pointer; width:80%; border-radius:0px 15px 15px 0px;" onclick="VerNoVer('devolver','envases')">
                    <i class='fas fa-wine-bottle'></i> Devolver Envases @if($usr) de {{$usr->nombre}} @endif
                    $ <span id="totalfrascos"> </span>
                </div>
            </div>

            <div class="row" id="sale_devolverenvases" style="display:none;">
                @foreach($envases as $e)
                    <div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div style="font-size:110%;font-weight:bold;">
                                <i class='fas fa-wine-bottle'></i> {{$e->fco_nombre}}
                            </div>
                            <div style="font-size:90%; color:gray;">{{$e->fco_describe}}</div> 
                            <span> $ {{$e->fco_costo}}</span>
                            <input type="number"
                                id="prod_{{$e->fco_id}}frasco" 
                                onchange="subtotal('{{$e->fco_id}}','frasco','{{$e->fco_costo }}');"
                                onkeyup="subtotal('{{$e->fco_id}}','frasco','{{$e->fco_costo }}');"
                                min='0'
                                class="producto"
                                name="envase_{{$e->fco_id}}">
                            <!-- ------ muestra subtotal --------- -->
                            <span id="subtot_{{$e->fco_id}}frasco" class="subtotalDeEnvases" style="font-size:50%; color:gray; display:inline-block;"></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!--######################################################################################################################-->
        <!--######################################################################################################################-->
        <!--############################################# VENTA DE PRODUCTOS #####################################################-->
        <!--######################################################################################################################-->
        <div>
            <div class="row">
                <div style="background-color: rgb(184, 219, 249); padding:1.5rem; margin:1rem; font-size:120%; cursor:pointer; width:80%; border-radius:0px 15px 15px 0px;" onclick="VerNoVer('venta','productos')">
                    <i class='fas fa-comment-dollar'></i> Vender @if($idusr >0) a {{$usr->nombre}} (Precio {{$usr->estatus}}) @endif
                    $ <span id="totalventa"> </span>
                </div>
            </div>

            <div class="row" id="sale_ventaproductos" style="display:block;">
                @foreach($productos as $prod)
                    <?php 
                        if($idusr > 0){
                            if($usr->estatus == 'act'){
                                $precio = $prod->precioact;
                            }else{
                                $precio = $prod->precioreg;
                            }
                        }else{
                            $precio = $prod->precioreg;
                        }
                    ?>
                    @foreach(explode(',', $prod->variantes)  as $sabor)
                        <div class="col-lg-3 col-md-3 col-sm-12" style="margin:1rem; min-height:70px;">
                            <div style="display:block;">
                                <!-- ---------------------- Nombre   ----------------------- -->
                                <div style="display:inline-block; width:70%;">
                                    <span onclick="VerNoVer('{{$prod->id}}','{{$sabor}}');";>
                                        <i class='fa fa-info-circle' style='font-size:18px;color:#BDBDBD' ></i>
                                        <span style="font-size:1.7rem; font-weight:bold;">{{$prod->gpo}} {{$prod->nombre}} 
                                            <span style="color:gray;font-size:1.6rem">{{preg_replace("/_/"," ",$sabor)}}</span>
                                        </span>                                
                                        <span style="float: right;font-size:1.7rem; color:var(--cuadroTotal);">
                                            $ {{$precio}}
                                        </span>
                                        
                                    </span>
                                    
                                </div>  
                                <!-- ---------------------- Precio y cantidad  ----------------------- -->
                                <div style="float: right;">                             
                                    <input type='number'
                                        onchange="subtotal('{{$prod->id}}','{{$sabor}}','{{$precio}}');"
                                        onkeyup ="subtotal('{{$prod->id}}','{{$sabor}}','{{$precio}}');"
                                        id="prod_{{$prod->id}}{{$sabor}}" 
                                        class='producto'
                                        min='0' 
                                        name="venta_{{$prod->id}}@-{{$sabor}}">
                                    <!-- ------ muestra subtotal --------- -->
                                    <span id="subtot_{{$prod->id}}{{$sabor}}" class="subtotalDeProd" style="font-size:50%; color:gray; display:block;"></span>
                                </div>
                            </div>
                            <div style="display:block;">
                                <!--- ------------------------------- Seccion DE INFO OCULTA ------------------------------------------------>
                                <div class="" id="sale_{{$prod->id}}{{$sabor}}" style='display:none; font-size:0.9rem; margin-top:1rem;'> 
                                    <div style="overflow:auto;">
                                        @if($prod->img == '' || is_null($prod->img))
                                            <img src="{{ asset('/logo.png') }}" style='width:150px; margin:15px; float:left;'><br>
                                        @else                                        
                                            <img src={{asset("$prod->img")}} style="width:150px; margin:15px; float:left;"><br>
                                        @endif

                                        <p style="font-size:1.5rem;"> {{$prod->descripcion}}</p>
                                        <p style="font-size:1.5rem;">Presentación: {{$prod->presentacion}} </p>
                                        @if( auth()->user()->estatus == 'act')
                                            <div style="font-size:1.5rem;font-style:italic;display:inline-block;">Act $ {{$prod->precioact}} </div>  &nbsp; &nbsp;
                                            <div style="font-size:1.5rem;font-style:italic;display:inline-block;">Reg $ {{$prod->precioreg}} </div>   &nbsp; &nbsp;
                                            <div style="font-size:1.5rem;font-style:italic;display:inline-block;">Pub $ {{$prod->preciopub}} </div> 
                                        @endif

                                        <p style="font-size:1.5rem;">Responsable: {{$prod->responsable}}</p>
                                        
                                        @if($prod->mintipo == '0')
                                            <p style="font-size:1.5rem;"> Este producto no requiere ningún mínimo de volumen para que el proveedor lo traiga.</p>
                                        @elseif($prod->mintipo =='1')
                                            <p style="font-size:1.5rem;"> Se requiere un mínimo de {{$prod->min}} unidades de este producto para que el proveedor lo traiga.</p>
                                        @elseif($prod->mintipo =='2')
                                            <p style="font-size:1.5rem;"> Se requiere un mínimo de compra de $ {{$prod->min}} pesos al proveedor para que nos  traiga sus productos.</p>
                                        @endif
                                    </div>

                                    <div>
                                        <b> &nbsp; Productor:</b>  <a href='../productores.php#{{$prod->proveedor}}' style='color: inherit; text-decoration: none' target='new'>{{$prod->proveedor}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>



    </div>
    @push('scripts')
    <script>
        function subtotal(id,sabor,precio) {            
            //CALCULA SUBTOTAL DE CADA PRODUCTO DE VENTA, CALCULA EL TOTAL
            //DE VENTAS DE PRODUCTOS
            var n1 = document.getElementById('prod_'+id+sabor).value
            //-------------------------------- Corrige valores nulos
            if(n1 == null  || n1 == undefined  || n1 == "")  {n1='0';}
            
            //-------------------------------- Calcula el subtotal de venta y de frascos de cada producto
            var subtotalVentas =  parseFloat(n1) * parseFloat(precio);
            document.getElementById('subtot_'+id+sabor).innerHTML = subtotalVentas
            
            //--------------------------------  Calcula el total de FRASCOS
            cadaUno=document.getElementsByClassName('subtotalDeEnvases');
            var x=0; var totFrascos=0;
            for (i=0, max=cadaUno.length; i<max; i++) {
                x = parseFloat(cadaUno[i].innerHTML);
                if(isNaN(x) || x=="" || x==null) {x='0';}
                totFrascos += parseFloat(x);
            }
            document.getElementById('totalfrascos').innerHTML = totFrascos;


            //--------------------------------  Calcula el total de ventas
            cadaUno=document.getElementsByClassName('subtotalDeProd');
            var x=0; var totVentas=0;
            for (i=0, max=cadaUno.length; i<max; i++) {
                x = parseFloat(cadaUno[i].innerHTML);
                if(isNaN(x) || x=="" || x==null) {x='0';}
                totVentas += parseFloat(x);
            }
            document.getElementById('totalventa').innerHTML = totVentas;

            //--------------------------------  Calcula el total de DEVOLUCIONES
            cadaUno=document.getElementsByClassName('subtotalDeDevoluciones');
            var x=0; var totalDevol=0;
            for (i=0, max=cadaUno.length; i<max; i++) {
                if (cadaUno[i].checked) {
                    totalDevol += parseFloat(cadaUno[i].value);
                }
            }
            //console.log('Devuelve',cadaUno, totalDevol, typeof(cadaUno))
            document.getElementById('totaldevuelve').innerHTML = totalDevol;
            
           
            //-------------------------------- Calcula el gran total (ventas, frascos y devoluciones)
            var GranTotal= totVentas + totFrascos + totalDevol 
            document.getElementById('GranGranTotal').innerHTML = GranTotal;
            document.getElementById('GranGranTotal2').value = GranTotal;
            //console.log(n1,precio,totVentas)

            //-------------------------------- Ante cualquier movimeinto, Oculta transferencia
            var x = document.getElementsByClassName('BotonesEspeciales');
                for (var i = 0; i < x.length; i++) {
                x[i].style.display= "none";
                }
        }

        //---------------------------------------- para select2
        document.addEventListener('livewire:load',function(){
            $('.select2').select2()
            $('.select2').on('change',function(){
                @this.set('idusr',this.value);
            })
        })
    </script>
    @endpush
</div>
