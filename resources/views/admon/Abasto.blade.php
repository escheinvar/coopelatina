@extends('plantillas.Basico')

@section('title')Abasto @endsection
@section('description') Recepción de productos en la Coope @endsection
@section('content')
    <form method="post">
        @csrf
        @livewire('admon.abasto-live-component')
    </form>
@endsection