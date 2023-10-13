@extends('plantillas.Basico')
@section('title')Calendario @endsection
@section('description') Calendario de entregas y pedidos @endsection


<style>
    
</style>

<?php $GranVariable ='conMenuHome'; ?>
@section('content')
    <h3>{{session('ProxChoro')}}</h3>
    @livewire('calendario-livewire-component')
@endsection


