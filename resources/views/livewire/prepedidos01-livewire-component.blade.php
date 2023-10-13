<div>
    <div>   
        <?php
            $meses=session('arrayMeses');
            $diaSem=session('arraySemana');
            $proxCom1=session('ProxCom1date');
            $proxCom2=session('ProxCom2date');
            $suma1=0; $suma2=0;
            $GranTotal=0;
        ?>
        
        <h1>{{auth()->user()->nombre}}, (precio {{auth()->user()->estatus}}) confirma tu pre pedido para {{ $meses[session('ProxPedido')['mes']] }} </h1>

        @if($anualidad > 0)
            <?php $GranTotal = $anualidad; ?>
            <div class="col-md-10 col-sm-12" style="background-color:rgb(234, 236, 255); margin:1rem;padding:1rem;">            
                <div style="background-color:rgb(75, 66, 102);color:white; padding:8px; ">
                    <b>Pago de anualidad</b>
                </div>
                <div style="display:inline-block; width:90%"><b> 1 </b> @if(auth()->user()->estatus =='pru') Pago inicial @else Renovación @endif por un año a partir del día de pago</div>
                <div style="display:inline-block;">${{$anualidad}}</div>
                
            </div>
        @endif

        <!-- ----------------------------------- PRIMER ENTREGA -------------------------------------------------- -->
        <div class="col-md-5 col-sm-12" style="background-color:rgb(234, 236, 255); margin:1rem;padding:1rem;">            
            <div style="background-color:rgb(75, 66, 102);color:white; padding:8px; ">
                <b>Primer entrega</b><br> 
                {{ $diaSem[session('ProxCom1date')['diasem']] }} {{ session('ProxCom1date')['dia'] }} de {{$meses[session('ProxCom1date')['mes']]}}
            </div>
            <div style="margin:1.5rem;">
                @foreach($com1 as $i)
                    <?php $suma1=$suma1+$i['cant']; $GranTotal = $GranTotal + ($i['precio'] * $i['cant']); ?>
                    <div style="display:inline-block; width:90%"><b>{{$i['cant']}}</b> &nbsp; {{$i['prodName']}} (<span style="color:gray;">{{$i['presenta']}}, ${{$i['precio']}}</span>) </div>
                    <div style="display:inline-block;">${{$i['precio']*$i['cant']}}</div>
                @endforeach
            </div>
            <div style="background-color:rgb(75, 66, 102);color:white; ">Total: {{$suma1}} productos</div>
        </div>

        <!-- ----------------------------------- SEGUNDO ENTREGA -------------------------------------------------- -->
        <div class="col-md-5 col-sm-12" style="background-color:rgb(234, 236, 255); margin:1rem;padding:1rem;">
            <div style="background-color:rgb(75, 66, 102);color:white; padding:8px; ">
                <b>Segunda entrega</b><br>
                {{ $diaSem[session('ProxCom2date')['diasem']] }} {{ session('ProxCom2date')['dia'] }} de {{$meses[session('ProxCom2date')['mes']]}}
            </div>
            <div style="margin:1.5rem;">
                @foreach($com2 as $i)
                    <?php $suma2=$suma2+$i['cant']; $GranTotal = $GranTotal + ($i['precio'] * $i['cant']);?>
                    <div style="display:inline-block; width:90%"><b>{{$i['cant']}}</b> &nbsp; {{$i['prodName']}} (<span style="color:gray;">{{$i['presenta']}}, ${{$i['precio']}}</span>) </div>
                    <div style="display:inline-block;">${{$i['precio']*$i['cant']}}</div>
                @endforeach
            </div>
            <div style="background-color:rgb(75, 66, 102);color:white;">Total: {{$suma2}} productos</div>
        </div>
        
        
        <div class="col-md-12 col-sm-12">
            <div style="font-size:2.5rem;"><br>
                Tienes hasta el {{ $diaSem[session('ProxPedend')['diasem']] }}  {{ session('ProxPedend')['dia'] }}  {{ $meses[session('ProxPedend')['mes']] }}  para realizar tu pago.
                De lo contrario, este pre pedido será cancelado.<br>
            </div>
        </div>
        
        <!-- ----------------------------------------- BOTÓN --------------------------------------------------- -->
        <div class="col-md-12 col-sm-12"><br>
            <div style="font-size:3.5rem;">Total: $ {{$GranTotal}} </div>
            <a href="/prepedido"><button class="btn btn-default btn-lg" type="button" style="display:{{$oculta}};"> Cancelar </button></a>
            @if( $GranTotal > 0 )
                <button class="btn btn-primary btn-lg" type="button" wire:click="CrearPrePedido" style="display:{{$oculta}};">Confirmar</button>
            @endif
            
        </div>
        
    </div>
    
    @push('scripts')
        <script type="text/javascript">           
            function NoOculta(prod,tipo) {
                var x = document.getElementById('sale_'+prod+tipo);
                x.style.display = "block";
            }
        </script>
    @endpush


</div>
