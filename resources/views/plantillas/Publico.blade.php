<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> @yield('title') </title>
        <meta name="description" content=@yield('description')>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- inicia boostrap --->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <!-- w3 icons -->
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">   
        <!--- ----------------------- w3 fontawesome escheinvar----------------------- -->
        <script src='https://kit.fontawesome.com/c94ac3fa5d.js' crossorigin='anonymous'></script>     
        @livewireStyles

        <link rel="stylesheet" type="text/css" href="{{ asset('/css/EstiloPublico.css') }}" />
        <script type="text/javascript" src="{{ asset('/js/MyJs.js') }}"></script>
    </head>
    <body>
        <main>
            <nav>
               Cooperativa de consumo Integración Latinoamericana<br>
               <a href="/">Quiénes Somos</a> | cómo participar | productos | <a href="/productores">productores</a> | contacto | <a href="/login">Ingresar al sistema</a>
               <h3> @yield('seccion') </h3>
            </nav>
            
              

            <div class="container">
                @yield('content')
            </div>

        @livewireScripts
    </main>
    </body>
</html>