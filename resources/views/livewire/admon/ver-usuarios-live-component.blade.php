<div>
    <!-- --------------------------------------------------------------------------------------------------------- -->
    <!-- --------------------------------------------- NUEVO USUARIO --------------------------------------------- -->
    <!-- --------------------------------------------------------------------------------------------------------- -->
    <div>
        <div class="row">
            <div style="margin:2rem;">
                <button type="button" class="btn btn-primary" style="float:right; width:200px;" data-toggle="modal" data-target="#NuevoUsr" wire:ignore.self wire:click="defineType('nuevo','0')">
                    <i class="fas fa-plus"></i> 
                    Nuevo cooperativista
                </button>
            </div>
        </div>
        

        <!-- --------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------- VER USUARIOS ----------------------------------------------- -->
        <!-- --------------------------------------------------------------------------------------------------------- -->
        <div class="row">
            <div class="form-group col-md-4">
                <input type="search" wire:model="buscar" placeholder="Buscar..." >
                @if($buscar != "")
                    <button type="button" class="btn btn-light" style='font-size:14px' wire:click="borrabuscar"><i class='far fa-times-circle'> Borrar</i></button>
                @endif
            </div>

            @if(session('usr_privs') == 'root' )
                <div class="form-group col-md-2">
                    <label>
                        <input type="radio" wire:model="VerOcultos" value="1" checked>
                        Solo activos
                    </label>
                    <label>
                        <input type="radio" wire:model="VerOcultos" value="0">
                        Solo ocultos
                    </label>
                </div>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th wire:click="orden('usr')" style="cursor:pointer;" scope="col">Usuario</th>
                        <th wire:click="orden('nombre')" style="cursor:pointer;" scope="col">Nombre</a></th>
                        <th wire:click="orden('ap_pat')" style="cursor:pointer;" scope="col">Apellidos</th>
                        
                        <th wire:click="orden('tel')" style="cursor:pointer;" scope="col">Teléfono </th>
                        <th wire:click="orden('mail')" style="cursor:pointer;" scope="col">Correo</th>
                        <th wire:click="orden('membrefin')" style="cursor:pointer;" scope="col">Fin de Membresia<br>Año/mes/dia</th>
                        <th wire:click="orden('dateregistro')" style="cursor:pointer;" scope="col">Antigüedad<br>Año/mes/dia</th>
                        <th wire:click="orden('priv')" style="cursor:pointer;" scope="col">Privs</th>
                        <th wire:click="orden('estatus')" style="cursor:pointer;" scope="col">Estatus</th>
                        
                    </tr>
                </thead>

                <tbody>
                    @foreach($usuarios as $i)
                        <tr>  <!-- --------------------------- nombre, aps, usrs, tel, mail ------------------------- -->
                            <th scope="row">{{$i->usr}}</th>
                            <td><a href="#" data-toggle="modal" data-target="#NuevoUsr"  wire:click="defineType('edita','{{$i->id}}')">{{$i->nombre}}</a></td>
                            <td>{{$i->ap_pat}} {{$i->ap_mat}}</td>
                            
                            <td>{{$i->tel}} </td>
                            <td>{{$i->mail}}</td>
                            
                            <td> <!-- ---------------------------  Membresía ------------------------- -->
                                <?php 
                                    $reg = new DateTime($i->membrefin);
                                    $hoy= new DateTime(date("Y-m-d"));
                                    $dif1 = $hoy ->diff($reg);
                                ?>
                                {{ $dif1->format("%y/%m/%d") }}
                            </td>
                            
                            <td> <!-- --------------------------- Antigüedad ------------------------- -->
                                <?php 
                                    $reg = new DateTime($i->dateregistro);
                                    #$hoy= new DateTime(date("Y-m-d"));
                                    $dif2 = $hoy ->diff($reg);
                                ?>
                                {{ $dif2->format("%y/%m/%d") }}
                            </td>
                            
                            <td> <!-- --------------------------- Privs ------------------------- -->
                                {{$i->priv}}
                            </td>
                            
                            <td> <!-- --------------------------- Estatus ------------------------- -->
                                {{$i->estatus}}
                            </td>
                            
                            {{--<td> <!-- --------------------------- Opciones ------------------------- -->
                                <a href="#" data-toggle="modal" data-target="#NuevoUsr"  wire:click="defineType('edita','{{$i->id}}')">
                                    <i class='fas fa-edit'>Editar</i>
                                </a> 
                            </td>--}}
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>
    </div>


    <!-- --------------------------------------------------------------------------------------------------------- -->
    <!-- --------------------------------------------- MODAL PARA AGREGAR NUEVO USUARIO -------------------------- -->
    <!-- --------------------------------------------------------------------------------------------------------- -->
    <div class="modal fade" id="NuevoUsr" tabindex="-1"  wire:ignore.self >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(51, 51, 173);color:white;">
                    <div class="row container" >
                        <H1> {{$text1}}  Cooperativista {{$MyId}}</H1>
                    </div>
                </div>
               
                <div class="modal-body">
                    <form method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="nombre">Nombre de Usuario:</label>
                                <input type="text" class="form-control @error('usr')error @enderror" wire:model="usr" value="{{old('usr')}}" @if($text1=='Editar') readonly @endif>
                                @error('usr')<error> Se requiere un nombre de usuario único {{$message}}</error> @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="nombre">Nombre:</label> 
                                <input type="text" class="form-control @error('nombre')error @enderror" wire:model="nombre" >
                                @error('nombre') <error>Se requiere indicar el(los) nombres del Cooperativista </error>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="nombre">Primer Apellido:</label>
                                <input type="text" class="form-control @error('ap_1')error @enderror" wire:model="ap_1" value="{{old('ap_1')}}">
                                @error('ap_1')<error> Se requiere al menos un apellido del Cooperativista</error> @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="nombre">Segundo Apellido:</label>
                                <input type="text" class="form-control @error('ap_2')error @enderror" wire:model="ap_2" value="{{old('ap_2')}}">
                                @error('ap_2')<error>Se requiere el segundo apellido</error> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="nombre">Teléfono:</label>
                                <input type="text" class="form-control @error('tel')error @enderror" wire:model="tel" value="{{old('tel')}}">
                                @error('tel')<error> Se requiere un teléfono celular válido</error> @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="nombre">Correo electrónico:</label>
                                <input type="text" class="form-control @error('mail')error @enderror" wire:model="mail" value="{{old('mail')}}">
                                @error('mail')<error> Se requiere un correo electrónico válido</error> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12"> 
                                <label for="nombre">Dirección:</label>
                                <input type="text" class="form-control @error('dir')error @enderror" wire:model="dir" value="{{old('dir')}}">
                                @error('dir')<error> Se requiere la dirección</error> @enderror
                            </div> 
                        </div>
                        <div class="row"> 
                            @if($text1=='Editar')
                                <div class="form-group col-md-12">
                                    <input type="checkbox" id="scales" name="scales" wire:model="CambiaPass">
                                    <label for="scales">Cambiar contraseña</label>
                                </div>
                                
                            @endif
                            @if($text1=='Agregar' OR $CambiaPass=='1')
                                <div class="form-group col-md-6">
                                    <label for="nombre">Ingresa Nueva Contraseña:</label>
                                    <input type="{{$InputType}}" class="form-control @error('password')error @enderror" wire:model="password" value="{{old('password')}}" style="display:inline;width:90%;">
                                    <i class="{{$IconoType}}" style='font-size:15px;' wire:click="MuestraUoculta()"></i>
                                    @error('password')<error> Se requiere una contraseña {{$message}}</error> @enderror
                                </div>

                                <div class="form-group col-md-5">
                                    <label for="nombre">Confirmar Contraseña:</label>
                                    <input type="password" class="form-control @error('password_confirmation')error @enderror" wire:model="password_confirmation">
                                    @error('password_confirmation') <error>Las contraseñas ingresadas no coinciden {{$message}}</error> @enderror
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="nombre">Estatus:</label>
                                <select class="form-control @error('estatus')error @enderror" wire:model="estatus" value="{{old('estatus')}}">
                                    <option value="">Seleccionar</option>
                                    @if($text1 == "Agregar")
                                        <option value="pru" >Prueba</option>
                                    @else
                                        <option value="reg">Registrado</option>
                                        <option value="act">Activo</option>
                                    @endif
                                </select>
                                @error('estatus')<error> Se requiere indicar un estatus</error> @enderror
                                    
                            </div>

                            <div class="form-group col-md-3">
                                <label for="nombre">Privilegios:</label>
                                <select class="form-control @error('privs')error @enderror" wire:model="privs"  value="{{old('privs')}}">
                                    <option value="">Seleccionar</option>
                                    @if($text1 == "Agregar")
                                        <option value="usr">Usuario</option>
                                    @else
                                        <option value="usr">Usuario</option>
                                        <option value="admon">Administración</option>
                                        <option value="teso">Tesorería</option>
                                        <option value="root">Super Admon</option>
                                    @endif
                                </select>
                                @error('privs')<error> Se requiere indicar un privilegio</error> @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Fecha de Registro:</label>
                                {{$dateregistro}}
                                <label>Vencimiento de anualidad:</label>
                                {{$membrefin}}
                                <label>Número de trabajos en anualidad:</label>
                                {{$trabajos}}
                                
                            </div>
                        </div>

                        <div class="row">
                            @if(auth()->user()->priv == 'root' )
                                @if($text1 == "Editar")
                                    <div class="form-group col-md-3">
                                        <label for="nombre">Activo:</label>
                                        <select class="form-control @error('privs')error @enderror" wire:model="activo"  value="{{old('activo')}}">
                                                <option value="1">Activo</option>
                                                <option value="0">Oculto</option>
                                        </select>
                                        @error('activo')<error> Se requiere indicar un privilegio</error> @enderror
                                    </div>
                                @endif
                            @endif
                        </div>
                    </form>
                   
                </div>
                <div class="row">
                    <div class="modal-footer">
                        <button type="reset"  class="btn btn-secondary col-4" wire:click="CancelSubmit"  data-dismiss="modal"><i class="fa fa-close"></i> Cancelar</button>
                        @if($text1=='Agregar')
                            <button type="button" class="btn btn-success col-5" wire:click="GuardaElNuevo" ><i class="fas fa-plus"></i> Agregar cooperativista</button>
                        @elseif($text1=="Editar")
                            <button type="button" class="btn btn-success col-5" wire:click="GuardaEdita" ><i class="fas fa-plus"></i> Editar datos</button>
                        @endif
                    </div>
                </div>
            </div>
    
        </div>
    </div>
    
    



</div>
 