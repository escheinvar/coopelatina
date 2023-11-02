<div>
    <h1>Los productores de la cooperativa</h1>
    Estos son los productores que participan en la Cooperativa:<br>


    <table class="table">
        <thead>
            <th>
                <td>
                </td>
            </th>
        </thead>
        <tbody>
            
            @foreach($prods as $i)
                <tr>
                    <td>
                        <div style="margin:2rem;">
                            <div>
                                <span style="cursor:pointer" onclick="VerNoVer('prod',{{$i->prod_id}})">
                                    <div style="display:flex;">
                                        <!-- ------------------- LOGO ------------------------- -->
                                        <div style=" margin:1rem;">
                                            @if($i->prod_logo =='')
                                                <div style=" width:150px; height:150px;background-color:aliceblue;"> </div>
                                            @else                                            
                                                <img src="{{ asset("$i->prod_logo") }}" style="width:150px; margin:15px;"><br>
                                            @endif
                                        </div>
                                        <div style="">
                                            <!-- ------------------- nombre ------------------------- -->
                                            <div style="font-size: 200%; font-weight:bold;">{{$i->prod_nombrelargo}}  </div>
                                            <!-- ------------------- tipo ------------------------- -->
                                            <div style="font-size: 100%; ">{{$i->prod_nombrecorto}}  <span style="color:gray;">{{$i->prod_tipo}}</span> </div>
                                            
                                            <!-- ------------------- descripción ------------------------- -->
                                            <p>{{$i->prod_descripcion}}
                                        </div>
                                    </div>
                                </span>
                            </div>
                            <style>
                                .campo i {color:darkgreen;}
                                .campo{margin:1rem;}
                               
                            </style>

                            <!-- --------------------------------- PARTE OCULTA --------------------------------- -->
                            <div style="display:none;" id="sale_prod{{$i->prod_id}}">
                                <!-- --------------------------  Carrusel de fotos ------------------------------- -->
                                @if($i->prod_fotos != '')
                                    <?php 
                                        $fotos=$i->prod_fotos;
                                        $fotos=explode(";",$i->prod_fotos); ?>
                                    @if(count($fotos)>0)
                                        <div style="text-align:center;">
                                            @foreach($fotos as $f)    
                                                <div style="display:inline-block; margin:1rem;">    
                                                    <img src="{{$f}}" style="max-width:400px;">
                                                    @if(auth()->user() AND in_array(auth()->user()->priv,  $clubToby))
                                                        <button class="btn btn-light" wire:click="BorrrarImgDeArray('{{$i->prod_id}}','{{$f}}')"><i class="fa fa-trash"></i> </button>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                               

                                <!-- --------------------------  Inicia Datos ------------------------------- -->
                                @if(auth()->user() AND in_array(auth()->user()->priv,  $clubToby))
                                    <div>
                                        Contacto: {{$i->prod_contacto}}<br>
                                    </div>
                                @endif

                                @if($i->prod_direccion != '')
                                    <div class="campo"><!-- Dirección -->
                                        <i class="bi bi-geo-alt"></i> {{$i->prod_direccion}}
                                    </div>
                                @endif

                                <div style="display:flex;">                                   
                                   @if($i->prod_http != '')
                                        <div class="campo"> <!-- Internet -->
                                            <i class="bi bi-globe"></i> <a href="{{$i->prod_http}}" target="new">{{$i->prod_http}} </a>
                                        </div>
                                    @endif

                                    @if($i->prod_correo != '')
                                        <div class="campo"> <!-- Correo -->
                                            <i class="bi bi-envelope"></i>   <a href="mailto:{{$i->prod_correo}}" target="new">{{$i->prod_correo}} </a>
                                        </div>
                                    @endif
                                    
                                    @if($i->prod_tel != '')
                                        <div class="campo"> <!-- Teléfono -->
                                            <i class="bi bi-telephone"></i>  <a href="tel:+{{$i->prod_tel}}" target="new">{{$i->prod_tel}} </a>
                                        </div>
                                    @endif
                                    
                                    {{--@if($i->prod_ != '')
                                        <div class="campo"> <!-- WhatsApp -->
                                            <i class="bi bi-whatsapp"></i> <a href="" target="new"> {{$i->prod_whats}} </a>
                                        </div>
                                    @endif
                                    
                                    @if($i->prod_ != '')
                                        <div class="campo"> <!-- Telegram -->
                                            <i class="bi bi-telegram"></i> <a href="" target="new">{{$i->prod_telegram}} </a>
                                        </div>
                                    @endif--}}
                                    
                                    @if($i->prod_facebook != '')
                                        <div class="campo"> <!-- Facebook -->
                                            <i class="bi bi-facebook"></i>  <a href="https://www.facebook.com/{{$i->prod_facebook}}" target="new">{{$i->prod_facebook}} </a>
                                        </div>
                                    @endif
                                    
                                    @if($i->prod_instagram != '')
                                        <div class="campo"> <!-- instagram -->
                                            <i class="bi bi-instagram"></i>  <a href="https://www.instagram.com/{{$i->prod_instagram}}" target="new">{{$i->prod_instagram}} </a>
                                        </div>
                                    @endif
                                    
                                    @if($i->prod_youtube != '')
                                        <div class="campo"> <!-- Youtube -->
                                            <i class="bi bi-youtube"></i>  <a href="https://www.youtube.com/{{$i->prod_youtube}}" target="new">{{$i->prod_youtube}} </a>
                                        </div>
                                    @endif


                                    <!-- -------------------------------------------- botón de editar .--------------------- -->
                                    @if(auth()->user() AND in_array(auth()->user()->priv,  $clubToby))
                                        <div style="font-size:2rem;margin:1rem;">
                                            <button type="button" style="float:right;" class="btn btn-primary" wire:click="AbrirModal('edit',{{$i->prod_id}})" data-toggle="modal" data-target="#EditarProductores"> 
                                                <i class="bi bi-pencil-square"></i> 
                                                Editar datos de Productor {{$i->prod_id}}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>




    @if(auth()->user() AND in_array(auth()->user()->priv,  $clubToby))
        
        @if($prodsInact->count() > 0)
            <H3>Productores Ocultos:</H3>
            @foreach($prodsInact as $i)
                <li><span style="cursor:pointer;" wire:click="AbrirModal('edit',{{$i->prod_id}})" data-toggle="modal" data-target="#EditarProductores"> {{$i->prod_nombrelargo}}</span></li>
            @endforeach
        @endif
        
        <hr>
        <div style="font-size:2rem;margin:1rem;">
            <button type="button" class="btn btn-primary" wire:click="AbrirModal('nvo','')"  data-toggle="modal" data-target="#EditarProductores">
                <i class="bi bi-plus-square-fill"></i> 
                Ingresar nuevo Productor
            </button>
        </div>
        <hr>


        <!-- ----------------- subir foto al carrusel -------------------- -->
        <div class="row">
            <div class="col-md-4">
                <label  class="form-label">Subir foto a carrusel de:</label>
                <select  class="form-control" wire:model="CarruselDeFoto" accept="image/png, image/jpeg" >
                    <option value="seleccionar">Indica el carrusel a subir la foto</option>
                    @foreach($prods as $i)
                        <option value="{{$i->prod_id}}">{{$i->prod_nombrelargo}} ({{$i->prod_nombrecorto}})</option>
                    @endforeach
                </select>
                @error('SubeFoto') <error>{{$message}}</error> @enderror
            </div>
            @if($CarruselDeFoto != '')
                <div class="col-md-5">
                    <label  class="form-label">Seleccionar Foto</label>
                    <input class="form-control" type="file" wire:model="SubeFoto" onclick="NoOculta('prod',{{$i->prod_id}})" accept="image/png, image/jpeg" >
                    @error('SubeFoto') <error>{{$message}}</error> @enderror
                </div>
            @endif
        </div>
        <div class="row">
            @if($SubeFoto)                                
                <div class="col-md-6">
                    <div wire:loading wire:target="SubeFoto">Cargando archivo....</div>
                    
                    
                    Previsualización de {{ $SubeFoto->getMimeType() }} :<br>
                    <img src="{{$SubeFoto->temporaryUrl()}}" style="width:200px; border=1px solid black;"><br>
                    <button class="btn btn-primary"  style="width:200px; margin:15px;" wire:click="SubirImgDeArray({{$CarruselDeFoto}})"><i class="fa fa-close"></i> Subir Foto</button>                               
                </div>
            @endif
        </div>
     




    @endif
    
    

    @if(auth()->user() AND in_array(auth()->user()->priv,  $clubToby))
        <!-- ######################################################################################################################################################-->
        <!-- ######################################################################################################################################################-->
        <!-- ############################################# MODAL PARA EDITAR o AGREGAR PRODUCTO  ##################################################################-->
        <!-- ######################################################################################################################################################-->
        <!-- ######################################################################################################################################################-->
        <div class="modal fade" id="EditarProductores" tabindex="-1"  wire:ignore.self >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: rgb(51, 51, 173); color:white;">
                        <div class="row container">
                            <H1> {{$text1}} Productor</H1>
                        </div>
                    </div>
                    
                    <div class="modal-body">
                        <div class="row">
                            <!-- ------------------------------- Nombre corto --------------------------- -->
                            <div class="form-group col-6">
                                <label>Nombre corto del Productor: <red>*</red></label>
                                <input type="text" wire:model="nombrecorto" class="form-control @error('nombrecorto')error @enderror" value="{{old('nombrecorto')}}">
                                @error('nombrecorto')<error>{{$message}}</error> @enderror 
                                <ch>Nombre corto que identifica al Productor x ej. La Coope</ch>
                            </div>

                            <!-- ------------------------------- Ocultar --------------------------- -->
                            <div class="form-group col-md-6 col-sm-6">
                                <?php if($activo=='0'){$activo='';} ?>
                                <label> Ocultar/Mostrar Productor</label><br>
                                <label class="switch">
                                    <input type="checkbox" wire:model="activo" >
                                    <div class="slider round"></div> 
                                </label>
                                @if($activo=='1')<span style="color:darkgreen;">Productor activo</span> @else <span style="color:gray;"> Productor oculto</span> @endif
                            </div>
                        </div>
                        <div class="row">
                            <!-- ------------------------------- Nombre completo --------------------------- -->
                            <div class="form-group col-12">
                                <label>Nombre completo del Productor: <red>*</red></label>
                                <input type="text" wire:model="nombrelargo" class="form-control @error('nombrelargo')error @enderror" value="{{old('nombrelargo')}}">
                                @error('nombrelargo')<error>{{$message}}</error> @enderror 
                                <ch>Nombre completo x ej: Cooperativa de Consumo Unidad Latinoamericana</ch>
                            </div>
                        </div>

                        <div class="row">
                            <!-- ------------------------------- Nombre de contacot --------------------------- -->
                            <div class="form-group col-6">
                                <label>Nombre del contacto: </label>
                                <input type="text" wire:model="contacto" class="form-control @error('contacto')error @enderror" value="{{old('contacto')}}">
                                @error('contacto')<error>{{$message}}</error> @enderror 
                            </div>

                            <!-- -------------------------------Teléfono --------------------------- -->
                            <div class="form-group col-6">
                                <label>Teléfono: </label>
                                <input type="text" wire:model="tel" class="form-control @error('tel')error @enderror" value="{{old('tel')}}">
                                @error('tel')<error>{{$message}}</error> @enderror 
                            </div>
                        </div>

                        <div class="row">
                            <!-- -------------------------------Correo--------------------------- -->
                            <div class="form-group col-6">
                                <label>Correo: </label>
                                <input type="text" wire:model="correo" class="form-control @error('correo')error @enderror" value="{{old('correo')}}">
                                @error('correo')<error>{{$message}}</error> @enderror 
                            </div>

                            <!-- ------------------------------- Dirección --------------------------- -->
                            <div class="form-group col-6">
                                <label>Dirección: </label>
                                <input type="text" wire:model="direccion" class="form-control @error('direccion')error @enderror" value="{{old('direccion')}}">
                                @error('direccion')<error>{{$message}}</error> @enderror 
                            </div>
                        </div>

                        <div class="row">
                            <!-- ------------------------------- Descripción --------------------------- -->
                            <div class="form-group col-12">
                                <label>Descripción del productor: </label>
                                <textarea wire:model="descripcion" row=4 class="form-control @error('descripcion')error @enderror" value="{{old('descripcion')}}"></textarea>
                                @error('descripcion')<error>{{$message}}</error> @enderror 
                            </div>
                        </div>

                        <div class="row">
                            <!-- ------------------------------- Página Web --------------------------- -->
                            <div class="form-group col-6">
                                <label>Página web: </label>
                                <input type="text" wire:model="http" class="form-control @error('http')error @enderror" value="{{old('http')}}">
                                @error('http')<error>{{$message}}</error> @enderror 
                            </div>

                            <!-- ------------------------------- Facebook --------------------------- -->
                            <div class="form-group col-6">
                                <label>Facebook: </label>
                                <input type="text" wire:model="facebook" class="form-control @error('facebook')error @enderror" value="{{old('facebook')}}">
                                @error('facebook')<error>{{$message}}</error> @enderror 
                            </div>
                        </div>

                        <div class="row">
                            <!-- ------------------------------- Instagram --------------------------- -->
                            <div class="form-group col-6">
                                <label>Instagram: </label>
                                <input type="text" wire:model="instagram" class="form-control @error('instagram')error @enderror" value="{{old('instagram')}}">
                                @error('instagram')<error>{{$message}}</error> @enderror 
                            </div>

                            <!-- ------------------------------- Youtube --------------------------- -->
                            <div class="form-group col-6">
                                <label>You Tube: </label>
                                <input type="text" wire:model="youtube" class="form-control @error('youtube')error @enderror" value="{{old('youtube')}}">
                                @error('youtube')<error>{{$message}}</error> @enderror 
                            </div>
                        </div>

                        <div class="row">
                            <!-- ------------------------------- Tipo --------------------------- -->
                            <div class="form-group col-6">
                                <label>Tipo: </label>
                                <select wire:model="tipo" class="form-control @error('tipo')error @enderror" value="{{old('tipo')}}">
                                    <option value="">Seleccionar</option>
                                    <option value="Cooperativa">Cooperativa</option>
                                    <option value="Organización">Organización</option>
                                    <option value="Familiar">Familiar</option>
                                    <option value="Ocasion">Ocasion</option>
                                    <option value="Microempresa">Microempresa</option>
                                    <option value="Empresa">Empresa</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                @error('tipo')<error>{{$message}}</error> @enderror 
                            </div>

                            <!-- ------------------------------- Orden --------------------------- -->
                            <div class="form-group col-6">
                                <label>orden: </label>
                                <input type="text" wire:model="orden" class="form-control @error('orden')error @enderror" value="{{old('orden')}}">
                                @error('orden')<error>{{$message}}</error> @enderror 
                            </div>
                        </div>

                        <div class="row">
                            <!-- ------------------------------- Imágen --------------------------- -->
                            @if($logo == '' || is_null($logo))
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
                                    <img src={{asset("$logo")}} style="width:200px; margin:15px;"><br>
                                    <button class="btn btn-light"  style="width:200px; margin:15px;" wire:click="BorrrarImg({{$prodid}})"><i class="fa fa-close"></i> Borrar Imágen</button>
                                </div>
                            @endif
                        </div>  
                        
                    </div>
                    <!-- ---------------------------------------------------------------------- Pie de modal de pruductores  -------------------------------------------------------- -->
                    <div class="modal-footer"> 
                        <div class="row" style="padding: 1rem; ">
                            <div class="col-md-4">
                                <button type="reset" class="btn btn-secondary"  data-dismiss="modal" style="margin:5px;"><i class="fa fa-close"></i> Cerrar</button></a>
                            </div>
                            <div class="col-md-4">
                                @if($text1=='Nuevo')
                                    <button type="button" id='CierraModal' wire:click="GuardaEdita('00')"  class="btn btn-success" style="margin:5px;"><i class="fas fa-plus"></i> Agregar producto</button>
                                @elseif($text1=="Editar")
                                    <button type="button" id='CierraModal' wire:click="GuardaEdita({{$prodid}})" class="btn btn-success" style="margin:5px;"><i class="fas fa-plus"></i> Editar evento {{$prodid}}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @push('scripts')
    <script type="text/javascript">           
        function NoOculta(prod,tipo) {
            var x = document.getElementById('sale_'+prod+tipo);
            x.style.display = "block";
        }
    </script>
@endpush
</div>
