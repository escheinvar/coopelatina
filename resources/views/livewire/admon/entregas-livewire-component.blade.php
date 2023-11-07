<div>
    <h1>Entregas <i class='fas fa-people-carry' style="font-size: 110%"></i> </h1>
    Hoy es {{session('arraySemana')[date('w')]}} {{date("j")}} de {{session('arrayMeses')[date('m')]}}<br>
    @if(session('ProximaCom')[0]=='com1') Primer @else Segunda @endif entrega del mes ( {{(session('ProximaCom'))[0]}} )
</div>
