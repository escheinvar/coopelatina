
@extends('plantillas.Basico')
@section('title')Pre-Pedido @endsection
@section('description') Sitio para generar el pre-pedido del mes siguiente @endsection


<?php $GranVariable='conMenuHome'; ?>
@section('content')
    @livewire('prepedidos01-livewire-component',['prepedido'=>$confirma,'anualidad'=>$anualidad,])
@endsection
