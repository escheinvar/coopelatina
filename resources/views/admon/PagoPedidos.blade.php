@extends('plantillas.Basico')

@section('title')Pago Prepedidos @endsection
@section('description') Validar el pago de prepedidos @endsection
@section('content')
    @livewire('admon.pago-pedidos-live-component')
@endsection
