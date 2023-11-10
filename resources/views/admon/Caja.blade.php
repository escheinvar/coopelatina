@extends('plantillas.Basico')

@section('title')Caja @endsection
@section('description')Movimientos de caja @endsection
@section('content')

<h1>Caja</h1>

<a href="/cajaVentas"><button type="button">Venta/Envases/Devol</button></a>

    <form method="post" id="sale_cajaventa">
        @csrf
        @livewire('admon.caja-live-component')
    </form>

    
@endsection