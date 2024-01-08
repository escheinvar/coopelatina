@extends('plantillas.Basico')

@section('title')Productos @endsection
@section('description') Catálogo de productos @endsection
@section('content')
    <!--form method="post"-->
        @csrf
        @livewire('admon.productos-livewire-component')
    <!--/form-->
@endsection