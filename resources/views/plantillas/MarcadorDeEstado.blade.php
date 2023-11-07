<style>
    /*a:link   {font-size: 90%;  text-decoration: none;}
    a:visited{font-size: 90%;  text-decoration: none;}
    a:hover  {font-size: 95%;  text-decoration: none;}
    a:active {font-size: 90%;  text-decoration: none;}*/
    a.PagActiva {font-size: 110%;font-weight: bold;}
    a.momentoON {color:rgb(100, 99, 99); text-decoration: overline;text-decoration-thickness:2px;text-decoration-color:rgb(0, 170, 0);}
    a.momentoOFF {color:rgb(100, 99, 99);}
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
