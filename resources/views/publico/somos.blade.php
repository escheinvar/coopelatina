@extends('plantillas.Publico')

@section('title')Quienes somos @endsection
@section('description')  @endsection
@section('content')
    <img src="{{URL::asset('logo.png')}}">
    <h1> ¿Quienes somos </h1>

    
    <p>Somos un colectivo de vecinos y de productores que desde 2015 decidimos organizarnos en una Cooperativa de Consumo sin fines de lucro e independiente de cualquier partido político o instancia gubernamental y abierta a todo aquel interesado en participar en ella.</p>

    <p>Nuestro objetivo es generar un colectivo que nos permita hacer frente, de manera social y organizada a un sistema capitalista de consumo para poder conseguir productos sanos, responsables con el entorno y a precios justos con los productores responsables y para los consumidores.</p>
    
@endsection
