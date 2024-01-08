<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> @yield('title') </title>
        <meta name="description" content=@yield('description')>
        <meta name="viewport" content="width=device-width, initial-scale=1">
                
        <!--- ----------------------- JQuery ----------------------- -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        

        <!--- ----------------------- Boostrap ----------------------- -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"  integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    
        <!--- ----------------------- w3 fontawesome escheinvar----------------------- -->
        <script src='https://kit.fontawesome.com/c94ac3fa5d.js' crossorigin='anonymous'></script>
        
        <!-- ------------------------- SWEET ALERT 2 -------------------------------- -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- ------------------------- SELECT2 https://select2.org/ -------------------------------- -->
        <link href=" https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- ------------------------- Mi css, Mi Js  -------------------------------- -->
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/EstiloBasico.css') }}" />
        <script type="text/javascript" src="{{ asset('/js/MyJs.js') }}"></script>


        <style>
           *{font-size: 20px;}
        </style>    
        
        @livewireStyles
    </head>

    <body>

        <header>  
            <div class="cintillo">
                <a href="/"><img src="{{ asset('/logo.png') }}" style="width:30px;"></a> &nbsp; 
                Hola {{auth()->user()['nombre']}} | 
                Estatus: {{auth()->user()['estatus']}} | 
                Privilegios: {{auth()->user()['priv']}} | 
                @if(auth()->user()->estatus == 'pru' and session('vigencia') =='1' )
                    Estás en período de prueba. Te quedan {{session('FinMembre')}} días
                @elseif(auth()->user()->estatus == 'pru' and session('vigencia') =='0' )
                    <span class="cintillo" style="background-color:red; color:black;">Tu período de prueba ya venció</span>
                @else
                    @if(session('vigencia') == '0') 
                        <span class="cintillo" style="background-color:red; color:black;">Tu anualidad venció hace {{session('FinMembre')}} días </span>
                            
                    

                    @elseif(session('FinMembre') <= '45')
                            <span class="cintillo" style="color:orange;">Tu anualidad está por vencer. Le quedan {{session('FinMembre')}} días</span>
                    @else
                        <?php 
                            $fecha=new DateTime(auth()->user()->membrefin);
                            $diaMes=date("d",strtotime(auth()->user()->membrefin));
                            $Mes=session('arrayMeses')[date("n",strtotime(auth()->user()->membrefin))];
                            $Anio=date("y",strtotime(auth()->user()->membrefin));
                        ?>
                        Anualidad vigente (vence el {{$diaMes}} de {{$Mes}} del {{$Anio}})
                    @endif
                @endif
            </div>
         
        
        
            <!-- ----------------------------------------------------------Inicia Barra Menú Superior -------------------------------------------- -->
            <!-- ----------------------------------------------------------Inicia Barra Menú Superior -------------------------------------------- -->
            <!-- ----------------------------------------------------------Inicia Barra Menú Superior -------------------------------------------- -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#">@yield('title')</a>
                
                <!-- ------------ Icono para chico ----------------- -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        
                        
                        @if( in_array(auth()->user()['priv']  ,  ['root','admon','teso']) )
                            <!---------------------------------------------- INICIA MENÚ-HAMBURBUESA SUPERIOR DE ADMINISTRACIÓN --------------------------- -->
                            <li class="nav-item dropdown" style="z-index:99">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Catálogos
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/usuarios"><i class="bi bi-person"></i> Usuarios</a>
                                    <a class="dropdown-item" href="/productores"><i class='fas fa-tractor'></i> Productores</a>
                                    <a class="dropdown-item" href="/catprods"><i class="bi bi-cart4"></i> Productos</a>
                                    <a class="dropdown-item disabled" href="#"><i class='fas fa-wine-bottle'></i> Envases</a>
                                    <!--div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a-->
                                </div>
                            </li>


                            <li class="nav-item dropdown" >
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="z-index:9999">
                                    Pre-Entrega
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="z-index:9999">
                                    <a class="dropdown-item" href="/calendario">1) Definir calendario</a>
                                    <a class="dropdown-item" href="/pagoprepedidos">2) Pago prepedidos</a>
                                    <a class="dropdown-item" href="/listasabasto">3) Listas de abasto</a>
                                    <a class="dropdown-item" href="/abastecer">4) Recibir proveedores</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/trabajos">Alta de apoyos</a>
                                    <!--a class="dropdown-item" href="/ocasion">Productos Ocasión</a--> 
                                    <a class="dropdown-item disabled" href="#">Pizarrón</a>                                   
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Entrega
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown"  style="z-index:9999">
                                    <a class="dropdown-item @if(session('EnEntrega')=='0') disabled @endif disabled" href="#">6) Iniciar Entrega</a>
                                    <a class="dropdown-item @if(session('EnEntrega')=='0') disabled @endif" href="/entrega">7) Entregar productos</a>
                                    <a class="dropdown-item @if(session('EnEntrega')=='0') disabled @endif disabled" href="#">8) Finalizar Entrega</a>
                                    
                                </div>
                            </li>
                            
                            <!--li class="nav-item">
                                <a class="nav-link disabled"  href="">Caja</a>
                            </li-->

                            <li class="nav-item dropdown" style="z-index:9999">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Caja
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown" >
                                    <a class="dropdown-item" href="/cajaVentas">Ventas/envases/devoluciones</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/caja">Ingresos/Egresos/Movimientos</a>
                                    <a class="dropdown-item" href="/caja">Corte/Ver cajas</a>
                                    
                                </div>
                            </li>

                            <!---------------------------------------------- FIN MENÚ-HAMBURBUESA SUPERIOR DE ADMINISTRACIÓN --------------------------- -->
                        @endif
                    </ul>
                </div>
                
                <!---------------------------------------------- INICIA MENÚ SUPERIOR DERECHA ÍCONOS --------------------------- -->
                <div style="float: right;">
                    <ul class="navbar-nav mr-auto" style="display:inline-block;">

                        @if(session('UsrEnTrabajo')=='1')
                         <!-- ################## ENTREGAS ######################### -->
                         <li class="nav-item"  style="display:inline-block;">
                            <div class="iconito">
                                <a class="nav-link" href="/entrega"><i class='fas fa-people-carry'></i></a>
                            </div>
                         </li>
                        @endif

                        <!-- ################## HOME ######################### -->
                        <li class="nav-item"  style="display:inline-block;">
                            <div class="iconito">
                                <a class="nav-link" href="/inicio/{{auth()->user()->usr}}">     <i class="fas fa-home"></i></a>
                            </div>  
                        </li>
                        
                        <!-- ################## PREPEDIDOS ######################### -->
                        <li class="nav-item" style="display:inline-block;">
                            <div class="iconito">
                                <a class="nav-link" href="/prepedido"> <i class="bi bi-cart4"></i></a>
                            </div>  
                        </li>

                        <!-- ################## CALENDARIO ######################### -->
                        <li class="nav-item" style="display:inline-block;">
                            <div class="iconito">
                                <a class="nav-link" href="/calendario"> <i class="fas fa-calendar-alt"></i></a>
                            </div>  
                        </li>
                        <!-- ################## SALIR ######################### -->                        
                        <li class="nav-item" style="display:inline-block;">
                            <form method="POST" action="/fin" class="form-inline my-2 my-lg-0" style="display:inline;">
                                @csrf 
                                <a class="nav-link" href="#"><button class="btn btn-link" type="submit"><i class='fas fa-door-open'></i></button></a>
                            </form>
                        </li>
                        
                    </ul>
                </div>
            <!-- ---------------------------------------------------------- Termina Barra Menú Superior -------------------------------------------- -->               
            <!-- ----------------------------------------------------------Inicia Barra Menú Superior -------------------------------------------- -->
            </nav>
        </header>





        <div class="container-fluid">
            <!-- -------------------------------------------------- Inicia Opción de página con menú izquierdo home ------------------------------------ --> 
            <!-- -------------------------------------------------- Inicia Opción de página con menú izquierdo home ------------------------------------ --> 
            <!-- -------------------------------------------------- Inicia Opción de página con menú izquierdo home ------------------------------------ -->  
            @if(isset($GranVariable) AND $GranVariable=='conMenuHome')                  
                <div class="row flex-nowrap">
                    <div class="col-auto col-xs-1 col-md-2 col-xl-1 px-sm-1 px-0 bg-light">
                        <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-gray min-vh-75">
                          <small> Bienvedid@</small>
                            <hr>
                            <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menuHome">
                               
                                <li class="nav-item"> <!-- ------------ Menú: Home ----- -->
                                    <a href="/inicio/{{auth()->user()->usr}}" class="nav-link align-middle px-0" style="color:rgb(70, 70, 70); font-size:20px;">
                                        <i class="fas fa-home"></i> <span class="ms-1 d-none d-sm-inline">Inicio / Avisos</span>
                                    </a>
                                </li>

                                <li class="nav-item"> <!-- ------------ Menú: Calendario de entregas ----- -->
                                    <a href="/calendario" class="nav-link align-middle px-0" style="color:rgb(70, 70, 70); font-size:20px;">
                                        <i class="fas fa-calendar-alt"></i>  <span class="ms-1 d-none d-sm-inline">Calendario</span>
                                    </a>
                                </li>


                                <li class="nav-item"> <!-- ------------ Menú: Prepedido ----- -->
                                    <a href="/prepedido" class="nav-link align-middle px-0" style="color:rgb(70, 70, 70); font-size:20px;">
                                        <i class="bi bi-cart4"></i> <!--i class="bi bi-cart-fill"></i-->  <span class="ms-1 d-none d-sm-inline">Pre-pedidos</span>
                                    </a>
                                </li>
                                
                                {{--<li class="nav-item disabled" > <!-- ------------ Menú: Prod-ocasión ----- -->
                                    <a href="#" class="nav-link align-middle px-0" style="color:rgb(70, 70, 70); font-size:20px;">
                                        <i class="bi bi-gift"></i>  <span class="ms-1 d-none d-sm-inline">Pedido de ocasión</span>
                                    </a>
                                </li>--}}

                                <li class="nav-item"> <!-- ------------ Menú: Pedidos Activos ----- -->
                                    <a href="/MisPedidos/{{auth()->user()->usr}}" class="nav-link align-middle px-0" style="color:rgb(70, 70, 70); font-size:20px;">
                                        <i class="bi bi-cart-check-fill"></i>  <span class="ms-1 d-none d-sm-inline">Mis Pedidos</span>
                                    </a>
                                </li>

                                
                                <!-- ------------ Menú: Desplegable ----- -->
                                <!--li>
                                    <a href="#submenu1" data-bs-toggle="collapse" class="nav-link px-0 align-middle" style="color:rgb(70, 70, 70); font-size:20px;">
                                        <i class="fs-4 bi-speedometer2 disabled"></i> <span class="ms-1 d-none d-sm-inline">Mi Historial</span> 
                                    </a>
                                    <ul class="collapse nav flex-column ms-1" id="submenu1" data-bs-parent="#menuHome">
                                        <li><a href="#" class="nav-link px-0 disabled" style="color:rgb(70, 70, 70); font-size:20px;"> <span class="d-none d-sm-inline">Mis Trabajos</span>  </a></li>
                                        <li><a href="#" class="nav-link px-0 disabled" style="color:rgb(70, 70, 70); font-size:20px;"> <span class="d-none d-sm-inline">Mis Pedidos</span> P </a></li>
                                        <li><a href="#" class="nav-link px-0 disabled" style="color:rgb(70, 70, 70); font-size:20px;"> <span class="d-none d-sm-inline">Mis Compras</span> C </a></li>
                                        <li><a href="#" class="nav-link px-0 disabled" style="color:rgb(70, 70, 70); font-size:20px;"> <span class="d-none d-sm-inline">Mis Anualidades</span> A </a></li>
                                    </ul>
                                </li-->

                                <li class="nav-item"> <!-- ------------ Menú: Los Productores ----- -->
                                    <a href="/productores" class="nav-link align-middle px-0" style="color:rgb(70, 70, 70); font-size:20px;">
                                        <i class='fas fa-tractor'></i>  <span class="ms-1 d-none d-sm-inline">Los productores</span>
                                    </a>
                                </li>

                                <li class="nav-item"> <!-- ------------ Menú: Los Trabajos ----- -->
                                    <a href="/trabajos" class="nav-link align-middle px-0" style="color:rgb(70, 70, 70); font-size:20px;">
                                        <i class='fas fa-hard-hat'></i>   <span class="ms-1 d-none d-sm-inline">Los trabajos</span>
                                    </a>
                                </li>
                                
                                <li class="nav-item disabled"> <!-- ------------ Menú: Calendario de entregas ----- -->
                                    <a href="#" class="nav-link align-middle px-0 disabled" style="color:rgb(70, 70, 70); font-size:20px;">
                                        <i class="bi bi-chat-dots-fill"></i>  <span class="ms-1 d-none d-sm-inline">Opinar</span>
                                    </a>
                                </li>
                                
                                <li class="nav-item disabled"> <!-- ------------ Menú: Calendario de entregas ----- -->
                                    <a href="#" class="nav-link align-middle px-0 disabled" style="color:rgb(70, 70, 70); font-size:20px;">
                                        <i class='fas fa-pepper-hot'></i>  <span class="ms-1 d-none d-sm-inline">Recetas</span>
                                    </a>
                                </li>

                                
                            </ul>

                            <hr>
                            <!-- --------------------------------------- Dropdown de Usuario ------------------ -->
                            <div class="dropdown pb-4">
                                <a href="#" class="d-flex align-items-center text-black text-decoration-none dropdown-toggle"  style="font-size:20px;" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle"></i>
                                    <!--img src="#" alt="hugenerd" width="30" height="30" class="rounded-circle"-->
                                    <span class="d-none d-sm-inline mx-1">{{auth()->user()['usr']}}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1" style="font-size:20px;">
                                    <li><a class="dropdown-item" href="#">Editar mis datos</a></li>
                                    <li><a class="dropdown-item"  href="/MisPedidos/{{auth()->user()->usr}}">Mis Pedidos</a></li>
                                    <li><a class="dropdown-item" href="/trabajos">Mis Trabajos</a></li>
                                    <li><a class="dropdown-item" href="#">Mis Compras</a></li>
                                    <li><a class="dropdown-item" href="#">Mis Anualidades</a></li>
                                    <li><a class="dropdown-item" href="#">Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <form method="POST" action="/fin"  style="display:inline;">
                                        @csrf 
                                        <li><a class="dropdown-item" href="#"><button class="btn btn-link" type="submit" style="color:black;"><i class='fas fa-door-open'> Salir</i> </button></a></li>
                                    </form>

                                    
                                </ul>
                            </div>
                            <hr>
                            
                        </div>
                    </div>

                    <div class="container col-xs-10 col-md-10 col-xl-10" >
                    
                        @yield('content')
                        
                    </div>
                </div>
            <!-- -------------------------------- Termina Opción de página con menú izquierdo Home---------------------------------- -->
                
            @else
            <!-- -------------------------------- Termina Opción de página normal ---------------------------------- -->
                <div class="px-sm-0 px-md-5">
                    @yield('content') 
                </div>
            
            @endif
        </div>





        <footer>
            <div style="height:50px;">
                &nbsp;
            </div>

            <div class="cintillo">
                Sistema de la Cooperativa de Consumo Unidad Latinoamericana.
            </div>
        </footer>



        @livewireScripts
        <script src="{{ asset('/js/MyJs.js') }}"></script>       

        
        @stack('scripts')
    </body>
</html> 