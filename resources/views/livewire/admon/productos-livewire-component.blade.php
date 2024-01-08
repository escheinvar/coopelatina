<div>
    

    <div style="display:inline-block">
        <!-- ################################### Switch de Estado de PRODUCTO DE OCASIÓN ################################### -->
        <!-- ################################### Switch de Estado de PRODUCTO DE OCASIÓN ################################### -->
        <!-- ################################### Switch de Estado de PRODUCTO DE OCASIÓN ################################### -->
        <div style="padding:2rem"> 
            <label>Pedidos de ocasión</label><br>
            <label class="switch">
                <input type="checkbox" wire:model="EnOcasion" wire:click="CambiaEstadoOcasion">
                <div class="slider round"></div> 
            </label>  
            @if(session('ocasion')=='1')
                <span style="color:darkgreen;">Sí se está ofreciendo {{count($ocasion)}} productos de ocasión.</span> 
            @else
                <span style="color:gray;">No se ofrecen productos de ocasión.</span> <!--span style="color:gray;"> Abasto Desactivo</span-->
            @endif
        </div>
    </div>

    <div style="">
        <div style="font-size:2rem;margin:1rem; ">
            <button type="button" class="btn btn-primary" wire:click="AbrirModal('nvo','')"  data-toggle="modal" data-target="#EditarProducto"><i class="bi bi-plus-square-fill"></i> Ingresar nuevo producto</button>
        </div>
    </div>


    <h1>Catálogo de Productos</h1>
    <div style="font-weight:bold;">
        <div style="width:250px; display:inline-block;"> 
            <span wire:click="reordenaTabla('gpo')" style="cursor:pointer;">Grupo</span> /
            <span wire:click="reordenaTabla('nombre')" style="cursor:pointer;"> Nombre </span>
        </div>
        <div style="width:70px; display:inline-block;">
            <span wire:click="reordenaTabla('entrega')" style="cursor:pointer;">Entr. </span>
        </div>
        <div style="width:30px; display:inline-block">
            <span wire:click="reordenaTabla('activo')" style="cursor:pointer;">Ver</span>
        </div>
        <div style="width:200px; display:inline-block"> 
            <span wire:click="reordenaTabla('presentacion')" style="cursor:pointer;">Presentación</span>
         </div>
        <div style="width:400px; display:inline-block"> 
            <span wire:click="reordenaTabla('variantes')" style="cursor:pointer;">Sabores</span>
         </div>
        <div style="width:250px; display:inline-block">
            <span wire:click="reordenaTabla('proveedor')" style="cursor:pointer;"> Proveedor</span>
        </div>
        <div style="width:70px; display:inline-block"> 
            <span wire:click="reordenaTabla('costo')" style="cursor:pointer;">Costo</span>
        </div>

        <div style="width:70px; display:inline-block"> 
            <span wire:click="reordenaTabla('precioact')" style="cursor:pointer;">Act</span> 
        </div>
        <div style="width:70px; display:inline-block"> 
            <span wire:click="reordenaTabla('precioreg')" style="cursor:pointer;">Reg</span>
        </div>
        <div style="width:70px; display:inline-block"> 
            <span wire:click="reordenaTabla('preciopub')" style="cursor:pointer;">Pub</span> 
        </div>
        <div style="width:200px; display:inline-block"> 
            <span wire:click="reordenaTabla('responsable')" style="cursor:pointer;">Responsable</span>
        </div>
        
    </div>
    @foreach ($productos as $i)
        <div style="width:99%;padding:1rem;border-top:1px solid gray; @if($i->entrega=='oca' && session('ocasion')=='1') background-color:orange; @endif"">
            <div style="width:250px; display:inline-block; ">
                <a href="#" wire:click="AbrirModal('edit',{{$i->id}})" data-toggle="modal" data-target="#EditarProducto">
                    {{$i->gpo}} {{$i->nombre}}
                </a>
            </div>

            <div style="width:70px; display:inline-block; 
                @if($i->entrega=='oca') font-weight:bold; color:rgb(197, 129, 3); @endif">
                {{$i->entrega}}
            </div>

            <div style="width:30px; display:inline-block; ">
                @if($i->activo=='1') <i class="bi bi-eye"></i> @else <i class="bi bi-eye-slash-fill" style="color:rgb(197, 129, 3);"></i> @endif
            </div>

            <div style="width:200px; display:inline-block; ">
                {{$i->presentacion}}
            </div>

            <div style="width:400px; display:inline-block; ">
                {{str_replace(",", ", ",$i->variantes)}}
            </div>

            <div style="width:250px; display:inline-block; ">
                {{$i->proveedor}}
            </div>

            <div style="width:70px; display:inline-block; ">
                $ {{$i->costo}}
            </div>

            <div style="width:70px; display:inline-block; ">
                $ {{$i->precioact}}
            </div>

            <div style="width:70px; display:inline-block; ">
                $ {{$i->precioreg}}
            </div>

            <div style="width:70px; display:inline-block; ">
                $ {{$i->preciopub}}
            </div>

            <div style="width:70px; display:inline-block; ">
                {{$i->responsable}}
            </div>
        </div>
    @endforeach



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
                        {{--
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
                        --}}
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

</div>


