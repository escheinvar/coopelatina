
@extends('plantillas.Basico')
@section('title')Mis pedidos @endsection
@section('description') Sitio donde puedo ver mis pedidos @endsection


<?php $GranVariable='conMenuHome'; ?>
@section('content')
    @livewire('mispedidos-livewire-component') 
@endsection


