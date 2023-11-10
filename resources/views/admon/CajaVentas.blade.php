@extends('plantillas.Basico')

@section('title')Caja Ventas @endsection
@section('description')Ventas de productos @endsection
@section('content')
    <form method="post">
        @csrf
        @livewire('admon.caja-ventas-live-component')
    </form>   
@endsection