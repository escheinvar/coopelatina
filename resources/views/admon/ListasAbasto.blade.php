@extends('plantillas.Basico')

@section('title')Listas de abasto @endsection
@section('description') Revisar las listas de abasto @endsection
@section('content')
    <form method="post">
        @csrf
        @livewire('admon.listas-abasto-live-component')
    </form>
@endsection
