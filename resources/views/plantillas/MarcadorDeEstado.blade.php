<style>
    .momentoON{ font-size: 80%; text-align: center; background-color:rgb(166, 248, 166);/*box-shadow: 2px 2px 2px 1px rgb(219, 220, 221); background-color:rgb(241, 240, 255); background-color:rgb(146, 250, 146);*/ font-weight: bold;}
    .momentoOFF{font-size: 80%; text-align: center; /*box-shadow: 0px 0px 0px 0px aliceblue; background-color:aliceblue;*/ }
    a:link   {font-size: 90%;  color:rgb(48, 48, 48);text-decoration: none;}
    a:visited{font-size: 90%;  color:rgb(48, 48, 48);text-decoration: none;}
    a:hover  {font-size: 100%; color:rgb(48, 48, 48);text-decoration: none;}
    a:active {font-size: 120%; color:black;          text-decoration: none;}
    prueba{color:gold}
</style>

<?php $backcolOn="gold"; $backcolOff="aliceblue"; ?>
<div>
    <div style="display:inline-block;">
        <div style="display:flex;">
            <div class="@if(session('EnPedido')=='1') momentoON                    
                @else momentoOFF @endif">
                <a href="/calendario">Pedidos</a>
            </div>

            <div class="momentoOFF"> -> </div>

            <div class="@if(session('EnPagos')=='1' OR session('EnPedido')=='1') momentoON  
                @else momentoOFF @endif">
                <a href="/pagoprepedidos">Pagos</a>
            </div>

            <div class="momentoOFF"> -> </div>

            <div class="@if(session('EnPedido')=='0' AND session('EnPagos')=='' AND session('ListasAbasto')=='1')momentoON
                @else momentoOFF @endif">
                <a href="/listasabasto">Listas</a>
            </div>

            <div class="momentoOFF"> -> </div>

            <div class="@if(session('ListasAbasto') == '' AND session('EnPagos') == '' AND session('EnPedido') =='0' AND session('ProximaCom')[0]=='com1') momentoON
                @else momentoOFF @endif">
                <a href="/abastecer">Abasto1</a>
            </div>

            <div class="momentoOFF"> -> </div>

            <div class="@if(session('EnEntrega')=='1' AND session('EnPagos') == '' AND session('ProximaCom')[0]=='com1') momentoON
                @else momentoOFF @endif">
                <a href="#">Entrega1</a>
            </div>

            <div class="momentoOFF"> -> </div>

            <div class="@if(session('ListasAbasto') == '' AND session('EnPagos') == '' AND session('EnPedido') =='0' AND session('ProximaCom')[0]=='com2') momentoON
                @else momentoOFF @endif">
                <a href="#">Abasto2</a>
            </div>

            <div class="momentoOFF"> -> </div>

            <div class="@if(session('EnEntrega')=='1' AND session('EnPagos') == '' AND session('ProximaCom')[0]=='com2') momentoON
                @else momentoOFF @endif">
                <a href="#">Entrega2 </a>
            </div>
        </div>
    </div>
</div>
{{--
<!-- ------------------------------------------- -->
Session(EnPedido)={{session('EnPedido')}}<br>
Session(EnEntrega)={{session('EnEntrega')}}<br>
Session(EnPago)={{session('EnPagos')}}<br>
Session(Ocasion)={{session('Ocasion')}}<br>
Session(ListasAbasto)={{session('ListasAbasto')}}<br>
<!-- ------------------------------------------- -->    
--}}
