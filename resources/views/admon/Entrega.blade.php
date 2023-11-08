@extends('plantillas.Basico')

@section('title')Entrega @endsection
@section('description') Entrega de productos a Cooperativistas @endsection
@section('content')
    <form method="post">
        @csrf
        @livewire('admon.entregas-livewire-component')
    </form>
@endsection