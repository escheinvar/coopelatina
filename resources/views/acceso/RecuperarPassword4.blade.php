@extends('plantillas.Publico')

@section('title')Recuperar contraseña @endsection
@section('description')  @endsection
@section('seccion') Recuperar Contraseña @endsection
@section('content')
<br>
    <div class="col-md-6  recuadro">
    
        <h1>Reestablecer Contraseña</h1>
        <br>
       
       @if($aviso=='0')
            Lo sentimos, la solicitud de cambio de contraseña ya cacucó, <br>
            vas a tener que solicitar una nueva<br>
            

       @elseif($aviso=='1')
            Se cambió correctamente la contraseña.<br>
            Ya puedes ingresar con la nueva página.<br>
          
       @endif
    </div>
    <br>
    <a href="/login"> <i class='fas fa-arrow-left' style='font-size:20px'> Regresar</i></a>
@endsection



