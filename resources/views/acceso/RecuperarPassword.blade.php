@extends('plantillas.Publico')

@section('title')Recuperar contraseña @endsection
@section('description')  @endsection
@section('seccion') Recuperar Contraseña @endsection
@section('content')
<br>
    <div class="col-md-6  recuadro">
    
        <h1>Reestablecer Contraseña</h1>
        <br>
        <form role="form" method="post">
            @csrf
            <div class="row">
                <p>Ingresa el nombre de usuario de tu cuenta de la Coope. Te vamos a enviar un correo electrónico desde donde podrás continuar el procedimiento para generar tu nueva contraseña.
            </div>

            <div class="row">
                <div class="form-group" style="padding:1rem;">
                    <label class="control-label" for="usr">Usuario a recuperar:</label>
                    <input class="form-control" type="text" id="usr" name="usr" placeholder="Usuario" style="@error('usr')border:1px solid red; @enderror">
                    @error('usr')<font color="red"><small>{{$message}} Requiere un usuario válido </font></small>@enderror
                </div>                
            </div>
            
            <div class="row" style="padding:1rem;">
                <button class="btn btn-primary pull-right" type="submit">Continuar</button>
            </div>
        </form>
    </div>
@endsection



