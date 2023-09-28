@extends('plantillas.Publico')

@section('title')Recuperar contraseña @endsection
@section('description')  @endsection
@section('seccion') Recuperar Contraseña @endsection
@section('content')
<br>
    <div class="col-md-6  recuadro" style="padding:2rem;">
    
        <h1>Reestablecer Contraseña</h1>
        <br>
        @if($aviso =='nohay')
            <div>
                Lo sentimos!! No existe la solicitud de referencia.
            </div>
        @elseif($aviso=='caduco')
            Lo sentimos!! La solicitud de cambio de contraseña ya venció!!<br>
            Deberás solicitar otra.
        @else
            <form role="form" method="post">
                @csrf
                <div class="row">
                    <p><b>{{$usr}}</b>, ingresa tu nueva contraseña:
                </div>

                <div class="row">
                    <div class="form-group">
                        <label class="control-label" for="usr">Contraseña nueva:</label>
                        <input class="form-control" type="password" id="pass" name="pass" placeholder="" style="@error('pass')border:1px solid red; @enderror;width:90%;display:inline;">
                        <a href="#" onclick="VerNoVerPass('pass','iconito')"><i class="far fa-eye-slash" id="iconito" style="font-size:15px;"></i></a>
                        @error('pass')<font color="red"><small>{{$message}} </font></small>@enderror
                    </div>    
                        
                        
                    <div class="form-group">
                        <label class="control-label" for="usr">Verifica la Contraseña:</label>
                        <input class="form-control" type="password" id="pass_confirmation" name="pass_confirmation" placeholder="" style="@error('pass_confirmation')border:1px solid red; @enderror; width:90%;display:inline;">
                        <a href="#" onclick="VerNoVerPass('pass_confirmation','iconito2')"><i class="far fa-eye-slash" id="iconito2" style="font-size:15px;"></i></a>
                        @error('pass_confirmation')<font color="red"><small>{{$message}} </font></small>@enderror
                    </div>                
                </div>
                <br>
                <div class="row">
                    
                        <button class="btn btn-primary pull-right" type="submit" >Continuar</button>
                    
                </div>
            </form>
        @endif
    </div>
@endsection



