
@extends('plantillas.Basico')
@section('title')Productos de ocasion @endsection
@section('description') Sitio para administrar los pedidos de ocasion @endsection


<?php #$GranVariable='conMenuHome'; ?>
@section('content')
    @livewire('ocasion-livewire-component')
@endsection
