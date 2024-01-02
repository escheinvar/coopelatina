<div>
    <h1>Caja</h1>
 


        <!--######################################################################################################################-->
        <!--######################################################################################################################-->
        <!--##################################################### CAJA ###########################################################-->
        <!--######################################################################################################################-->
        <div style="background-color: rgb(159, 210, 255); padding:1rem; display:flex;">
            <div class="col-md-2 col-sm-12 col-xs-12" style="background-color: rgb(159, 210, 255); padding:1rem; ">
                <div class="row">
                    <div style="display: flex;">
                        <div class="form-group" style="display:inline-block;">
                            <label>Origen</label>
                            <select class="form-control" id="origencaja" name="caja_origen" style="width:100px;" wire:model="origen" @if(!in_array(auth()->user()->priv,$petitcomite)) disabled @endif>
                                <option value="caja">Caja</option>       <option value="banco">Banco</option>       <option value="teso">Tesoreria</option>
                            </select>
                        </div>                

                        <div class="form-group" style="display:inline-block;">
                            <label>Destino</label>
                            <select class="form-control" id="destinocaja" name="caja_destino" style="width:100px;"  wire:model="destino" @if(!in_array(auth()->user()->priv,$petitcomite)) disabled @endif>
                                <option value="caja">Caja</option>       <option value="banco">Banco</option>         <option value="teso">Tesoreria</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div style="display:flex">
                        <div class="form-check" style="display:inline-block;">
                            <input class="form-check-input" id="operacioncajaIn" name="caja_operacion" type="radio" value="ingreso" wire:model="operacion" @if($origen != $destino) disabled @endif>
                            <label class="form-check-label"> Ingreso </label>
                        </div>
                        
                        <div class="form-check" style="display:inline-block;">
                            <input class="form-check-input" id="operacioncajaEg" name="caja_operacion" type="radio" value="egreso"  wire:model="operacion"  @if($origen != $destino) disabled @endif>
                            <label class="form-check-label" for="exampleRadios2"> Egreso </label>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label >Monto:</label>
                        <input id="montoCaja" name="caja_monto" type="number" class="form-control" placeholder="Monto" style="width:200px;" wire:model="monto">
                        @error('monto')<error>{{$message}}</error>@enderror  
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12" style="background-color: rgb(159, 210, 255);padding:1rem; ">
                <div class="row">
                    <div class="form-group">
                        <label>Concepto</label>
                        <select class="form-control"  name="caja_concepto" style="width:200px;"  wire:model="concepto" @if($origen != $destino) disabled @endif>
                            @if($origen==$destino)
                                    <option value="">Indica la operación</option>
                                @if ($operacion=='ingreso')
                                    <option value="venta">Venta de producto</option>
                                    <option value="donativo">Donativo</option>
                                @elseif($operacion=='egreso')
                                    <option value="devolComprador">Devolución a comprador</option>
                                    <option value="pagoProovedor">Pago a proveedor</option>
                                    <option value="pagoInsumos">Pago de insumos</option>
                                    <option value="pagoServicios">Pago de servicios</option>
                                    <option value="pagoSalarios">Pago de salarios</option>
                                @endif
                                    <option value="corrijeCaja">Corrección de caja</option>
                                    <option value="prestamo">Préstamo</option>
                                    <option value="otro">Otro</option>
                            @else
                                <option value="">Movimiento de caja</option>
                            @endif
                        </select>
                        @error('concepto')<error>{{$message}}</error>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="form-group" >
                        <label>Observaciones</label>
                        <textarea class="form-control"  name="caja_observa" style="width:200px;"  wire:model="observa"></textarea>
                    </div>
                </div>
                <div class="row" style="width:40%">
                    <button type="button" class="btn btn-success" wire:click="cajaRegistra()" @if($monto == 0) disabled @endif>Registrar</button>
                </div>
            </div>
        </div>  

        <button class="btn btn-light" type="button" style="margin:1rem;" onclick="VerNoVer('ver','caja');">Ver caja</button>
        <button class="btn btn-success" type="button" style="margin:1rem;" onclick="VerNoVer('corte','caja');">Corte de caja</button>





        <!--######################################################################################################################-->
        <!--######################################################################################################################-->
        <!--################################################# VER CAJA ###########################################################-->
        <!--######################################################################################################################-->
        <div id="sale_vercaja" style="display:none;">
            <div class="row">
                Total de caja: {{$caja->sum('caja_caja')}}
                <div style="display:flex;">
                    <div class="form-group" >
                        <label>Caja: </label>
                        <select style="width:200px;" wire:model="hist_caja"  class="form-control">
                            <option value="caja">Caja</option>
                            <option value="teso">Tesoreria</option>
                            <option value="banco">Banco</option>
                        </select>
                        
                    </div>

                    <div class="form-group" style="display:inline-block;">
                        <label>Movimiento</label>
                        <select style="width:200px;" wire:model="hist_tipo"  class="form-control">
                            <option value="%">todos</option>
                            @foreach( $tiposCaja as $i)
                                <option value="{{$i->caja_tipo}}"> {{$i->caja_tipo}}</option>
                            @endforeach
                        </select>
                    
                    </div>

                    <div class="form-group" style="display:inline-block;">
                        <label>Del día</label>
                        <input type="date" wire:model="hist_mindate" class="form-control" style="width:200px;">
                    </div>
                    
                </div>
            </div>

            <div>
                <h1>Ver {{$hist_caja}}</h1>
                @if(count($caja) > 0)
                    
                    @if($caja->hasPages())
                        <!-- ------------------- cabecera de paginación -------------------------- -->
                        <ul class="pagination">
                            <li><a href="{{$caja->previousPageUrl()}}"> &laquo;</a></li>
                        </ul>
                        <ul class="pagination">
                            @foreach(range(1, $caja->lastPage()) as $i)
                                @if($i == $caja->currentPage())
                                    <li><a href="{{$caja->url($i)}}" style="background-color:rgb(159, 210, 255);">{{$i}}</a></li>
                                @else
                                    <li><a href="{{$caja->url($i)}}">{{$i}}</a></li>
                                @endif
                            @endforeach
                        </ul>
                        <ul class="pagination">
                            <li><a href="{{$caja->nextPageUrl()}}"> &raquo;</a></li>
                        </ul>
                        <!-- -------------------------- inicia tabla ----------------------- -->
                    @endif
                    <div style="font-weight:bold;">
                        <div class="row" style="">
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"  style="display:flex;">
                                <div style="width:40%;"> id </div>
                                <div style="width:60%;">Mov.</div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12"> Fecha</div>
                            <div class="col-lg-1 col-md-1 col-sm-12"> {{$hist_caja}}</div>
                            <div class="col-lg-2 col-md-2 col-sm-12">Observaciones</div>
                        </div>
                    </div>
                    @foreach($caja as $i)

                        <div class="row" style="border-bottom:1px dotted gray;">
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" style="display:flex;">
                                <div style="width:40%;">
                                    {{$i->caja_id}}
                                </div>
                                <div class="" style="width:60%;">
                                    {{$i->caja_tipo}}
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2 col-sm-12" style="display:flex;">
                                <div style="width:220px;"> 
                                    {{$i->updated_at}}
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-12" style="display:flex;">
                                <div style="width:200px;"> $
                                    @if($hist_caja == 'teso')
                                        {{$i->caja_teso}}
                                    @elseif($hist_caja == 'banco')
                                        {{$i->caja_banco}}
                                    @else
                                        {{$i->caja_caja}}
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <div style="width:400px;font-size:80%;color:gray;">
                                    {{$i->caja_observaciones}}
                                </div>
                            </div>
                        </div>
                    @endforeach            
                @else
                        --no hay registros de caja--
                @endif
            </div>
        </div>





        <!--######################################################################################################################-->
        <!--######################################################################################################################-->
        <!--################################################# CORTE DE CAJA ######################################################-->
        <!--######################################################################################################################-->
        <div id="sale_cortecaja" style="display:block;">
            <h1>Corte de caja</h1>
            @include('calculadora')
        </div>
    </div>
