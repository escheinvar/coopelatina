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
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"  integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script> -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
        <!--- ----------------------- w3 fontawesome escheinvar----------------------- -->
        <script src='https://kit.fontawesome.com/c94ac3fa5d.js' crossorigin='anonymous'></script>

        <!-- ------------------------- SWEET ALERT 2 -------------------------------- -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- ------------------------- Mi css, Mi Js  -------------------------------- -->
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/EstiloBasico.css') }}" />
        <script type="text/javascript" src="{{ asset('/js/MyJs.js') }}"></script>

        <style>
           
        </style>    
        
        @livewireStyles
    </head>
    <body>
        <header>
            <nav>
                Hola {{session('usr_name')}} | 
                Estatus: {{session('usr_estatus')}} | 
                Privilegios: {{session('usr_privs')}} | 
                Antigüedad: {{session('usr_membre')}}
                <br>
                Pizarrón | <a href="/prepedido">Pedidos</a> | Ventas | caja | <a href="/usuarios">Usuarios</a> | Entrega | Logística | <a href="/login">Salir</a>
                <br>
                @yield('menu')
            </nav>
        </header>

        <main>
            <div class="container"><div style="height: 125px;"></div>
                @yield('content')
            </div>
        </main>

        <footer>
            <div>Cooperativa de Consumo: Unidad Latinoamericana.</div>
        </footer>

        @livewireScripts
        <script src="{{ asset('/js/MyJs.js') }}"></script>       
    </main>
    </body>
</html> 