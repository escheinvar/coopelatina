@extends('plantillas.Basico')

@section('title')Caja @endsection
@section('description')Movimientos de caja @endsection
@section('content')

<form method="post" id="sale_cajaventa">
    @csrf
    @livewire('admon.caja-live-component')
</form>

@endsection