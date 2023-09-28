@extends('plantillas.Publico')

@section('title')Ingreso al Sistema @endsection
@section('description')  @endsection
@section('seccion') Ingreso @endsection

@section('content')
    <div class="col-md-6 center recuadro" style="padding:3rem;"><center>
       
        
        <h1>Bienvenid@</h1>
        <form role="form" method="post">
            @csrf
            
            <div class="row col-md-6">
                <div class="form-group">
                    <label class="control-label" for="usr">Usuari@:</label>
                    <input class="form-control" type="text" id="usr" name="usr" placeholder="Usuario" value="enrique{{old('usr')}}" style="@error('usr')border:1px solid red; @enderror; ">
                    @error('usr')<font color="red"><small>{{$message}} Requiere un usuario válido </font></small>@enderror
                </div>

                <div class="form-group">
                    <label class="control-label" for="password">Contraseña</label>
                    <input class="form-control" type="password" id="password" name="password" placeholder="Contraseña"  value="bla{{old('password')}}" style="@error('password')border:1px solid red; @enderror;display:inline;width:90%">
                    <a href="#" onclick="VerNoVerPass('password','iconito');"><i id='iconito' class="far fa-eye-slash" style='font-size:15px;'></i></a>
                    @error('password')<font color="red"><small>{{$message}} Requiere una contraseña correcta </font></small>@enderror
                    
                </div>       
            </div>
            
            <div style="color:red;"> 
                {{$mensaje}} 
            </div>

            <br>
            <div class="row col-md-6">
                <button class="btn btn-primary pull-right" type="submit">Ingresar</button>
            </div>
        </form>
        <div class="row col-md-6" style="margin:1rem; text-align: center;">
            <a href="/recupera_password">Olvidé mi contraseña</a>
        </div>
    </div>
@endsection