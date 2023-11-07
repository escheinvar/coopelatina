<style>
    /*
    .momentoON{ font-size: 80%; text-align: center; background-color:rgb(166, 248, 166); box-shadow: 2px 2px 2px 1px rgb(219, 220, 221); background-color:rgb(241, 240, 255); background-color:rgb(146, 250, 146); font-weight: bold;}
    .momentoOFF{font-size: 80%; text-align: center; box-shadow: 0px 0px 0px 0px aliceblue; background-color:aliceblue; }*/
    a.momentoON {color:rgb(0, 170, 0);}
    a.momentoOFF {color:rgb(100, 99, 99);}
    a:link   {font-size: 90%;  text-decoration: none;}
    a:visited{font-size: 90%;  text-decoration: none;}
    a:hover  {font-size: 95%;  text-decoration: none;}
    a:active {font-size: 90%;  text-decoration: none;}
    a.PagActiva {font-size: 100%;font-weight: bold;}
    prueba{color:gold}
</style>

<?php $backcolOn="gold"; $backcolOff="aliceblue"; ?> 
<div>
    <div style="display:inline-block;">
        <div style="display:flex;">
            <div>
                <a href="/calendario" class="
                    @if( preg_match("/.*calendario/", url()->current())) PagActiva @endif
                    @if(session('EnPedido')=='1') momentoON @else momentoOFF @endif
                ">Pedidos</a>
                
            </div>

            <div class="momentoOFF"> -> </div>

            <div>
                <a href="/pagoprepedidos" class="
                    @if( preg_match("/.*pagoprepedidos/", url()->current())) PagActiva @endif
                    @if(session('EnPagos')=='1' OR session('EnPedido')=='1') momentoON @else momentoOFF @endif
                ">Pagos</a>
            </div>

            <div class="momentoOFF"> -> </div>

            <div>
                <a href="/listasabasto" class="
                    @if( preg_match("/.*listasabasto/", url()->current())) PagActiva @endif
                    @if(session('EnPedido')=='0' AND session('EnPagos')=='' AND session('ListasAbasto')=='1')momentoON @else momentoOFF @endif
                ">Listas</a>
            </div>

            <div class="momentoOFF"> -> </div>

            <div>
                <a href="/abastecer" class="
                    @if( preg_match("/.*abastecer/", url()->current())) PagActiva @endif
                    @if(session('ListasAbasto') == '' AND session('EnPagos') == '' AND session('EnPedido') =='0' AND session('ProximaCom')[0]=='com1') momentoON @else momentoOFF @endif
                ">Abasto1</a>
            </div>

            <div class="momentoOFF"> -> </div>

            <div class="">
                <a href="#" class="
                    @if(session('EnEntrega')=='1' AND session('EnPagos') == '' AND session('ProximaCom')[0]=='com1') momentoON @else momentoOFF @endif
                ">Entrega1</a>
            </div>

            <div class="momentoOFF"> -> </div>

            <div>
                <a href="#" class="
                    @if(session('ListasAbasto') == '' AND session('EnPagos') == '' AND session('EnPedido') =='0' AND session('ProximaCom')[0]=='com2') momentoON @else momentoOFF @endif
                ">Abasto2</a>
            </div>

            <div class="momentoOFF"> -> </div>

            <div>
                <a href="#" class="
                @if(session('EnEntrega')=='1' AND session('EnPagos') == '' AND session('ProximaCom')[0]=='com2') momentoON @else momentoOFF @endif
                ">Entrega2 </a>
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
