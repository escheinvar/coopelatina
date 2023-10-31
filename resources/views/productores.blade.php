
@if(auth()->user())
    <?php $plantilla="plantillas.Basico"; $GranVariable='conMenuHome'; ?>
@else
    <?php $plantilla="plantillas.Publico"; ?>
@endif

@extends($plantilla)
@section('title')Productores de la Coope @endsection
@section('description') Productores que participan de la Cooperativa @endsection

@section('content') 
    @livewire('productores-livewire-component')
@endsection