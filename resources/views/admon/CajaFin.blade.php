@extends('plantillas.Basico')

@section('title')Caja @endsection
@section('description')Movimientos de caja @endsection
@section('content')
    <!-- GranTotal devuelve envase venta -->

    <div style="margin:5rem;padding:3rem; background-color: rgb(184, 219, 249);">
        <h1>Registro de Caja</h1>
        
        <?php
            $total='0';
            if(isset($salida['venta'])){$total += count($salida['venta']); }
            if(isset($salida['envase'])){$total += count($salida['envase']);}
            if(isset($salida['devuelve'])){$total += count($salida['devuelve']);}
            if($total > 1){$text="Se registraron ".$total." movimientos:";
            }else{$text="Se registró un movimiento:";}
        ?>
       {{$text}}

        @if(isset($salida['venta']))
            <div style="padding:1rem;">
                <h3>Venta de:</h3>
                @foreach ($salida['venta'] as $i)
                    <div style="padding:1rem;display:flex;">
                        <div style="width: 30px;">{{$i['cant']}}</div>
                        <div style="width: 300px;">{{$i['nombre']}} {{$i['sabor']}}</div>
                        <div style="width: 50px;"> ${{$i['precio']}}</div>

                    </div>
                @endforeach    
            </div>
        @endif

        @if(isset($salida['envase']))  
            <div style="padding:1rem;">
                <h3>Devolución de envases:</h3>
                @foreach ($salida['envase'] as $i)
                    <div style="padding:1rem;display:flex;">
                        <div style="width: 30px;">{{$i['cant']}}</div>
                        <div style="width: 300px;">{{$i['nombre']}}</div>
                        <div style="width: 50px;"> ${{$i['precio']}}</div>
                    </div>
                @endforeach 
            </div>   
        @endif

        @if(isset($salida['devuelve']))  
            <div style="padding:1rem;">
                <h3>Devolución de productos:</h3>
                @foreach ($salida['devuelve'] as $i)
                    <div style="padding:1rem;display:flex;">
                        <div style="width: 330px;">{{$i['producto']}}</div>
                        <div style="width: 50px;"> ${{$i['precio']}}</div>
                    
                @endforeach     
            </div>
        @endif

        <div>
            <div style="width:380px;text-align:right;margin:1rem;font-size:120%;font-weight:bold;">
                Total: {{$salida['GranTotal']}}
            </div>
        </div>

        <a href="/cajaVentas"><button type="button" class="btn btn-primary"><- Ventas </button></a>
        <a href="/caja"><button type="button" class="btn btn-primary"><- Caja </button></a>
    </div>
    
                
    
@endsection