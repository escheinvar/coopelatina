@extends('plantillas.Publico')

@section('title')Recuperar contraseña @endsection
@section('description')  @endsection
@section('seccion') Recuperar Contraseña @endsection
@section('content')
<br>
    <div class="col-md-6  recuadro" style="padding:3rem;">
        <h1>Reestablecer Contraseña</h1>
        <br>
        Para continuar, revisa el correo que fue enviado a la dirección:<br>
        <br>
        {{$datos['texto']}} <br>
    </div>

    <br>
    <a href="/login"> <i class='fas fa-arrow-left' style='font-size:20px'> Regresar</i></a>
@endsection



