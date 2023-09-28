
@extends('plantillas.Basico')
@section('title')Pre-Pedido @endsection
@section('description') Sitio para generar el pre-pedido del mes siguiente @endsection

@section('menu')
    <!-- -->
@endsection

@section('content')
    @livewire('prepedidos-livewire-component')
@endsection
{{--

@extends('plantillas.Basico')
    @section('title')Pre-Pedido @endsection
    @section('description') Sitio para generar el pre-pedido del mes siguiente @endsection
    
    @section('menu')
    <form method="post">
        <div style="float:right; margin-right:5rem;display:flex">
            <div style="font-size:4rem;">Total $ <span id="CalculadoraTotal">0</span> </div>
            
            <div><button class="agregar" type="submit"> <i class="fas fa-plus"> Hacer Prepedido</i></button></div>
        </div>
    @endsection
    
    
    @section('content')
        <!-- -- ojo: continúa form  ---- -->
            @csrf
            @foreach($todo as $key=>$value)
                <?php /*
                    #### Genera vector de sabores   
                    $sabores=explode(',', $value->variantes);
                    $num=count($sabores);
                    ### Quita palabras repetidas de nombre (cuando ya está en gpo)
                    $value->nombre = preg_replace("/$value->gpo/i","",$value->nombre);
                    ### Determina precio
                    if( session("usr_estatus") == 'act'){$precio = $value->precioact;}else{ $precio = $value->precioreg; }
                    ### Determina comportamiento según entrega:
                    if($value->entrega == 'com1'){
                        $com1act=""; $com1text="Com 1"; $com2act="disabled"; $com2text="";
                    }else if($value->entrega == 'com2'){
                        $com1act="disabled"; $com1text=""; $com2act=""; $com2text="Com 2";
                    }else if($value->entrega =='com12'){
                        $com1act=""; $com1text="Com 1"; $com2act=""; $com2text="Com 2";
                    }else if($value->entrega == 'comid'){
                        $com1act=""; $com1text="Com ID x 2"; $com2act="disabled"; $com2text="Com ID x 2";
                    }*/
                ?>
                <!-- ------------------------------------------------------------------------------------------------------------------------------------- -->
                <!-- ------------------------------------------------------------------------------------------------------------------------------------- -->
                <!-- -------------------------------------- {{$value->nombre}} ----------------------------------------------------------------------------- -->
                
                <div class="recuadro col-xs-12 col-md-5" >
                    <!--- ------------------------------- Seccion texto de título ------------------------------------------------>
                    <div style="margin-top:0.2rem;">
                        <div style="height:45px;">
                            <!-- ---------------------- icono de desplegable ----------------------- -->
                            <span onclick="VerNoVer('{{$key}}','{{$value->id}}');";>
                                <i class='fa fa-info-circle' style='font-size:28px;color:#BDBDBD' ></i>
                                <!-- ---------------------- Grupo y nonmbre (desplegables)  ----------------------- -->
                                <span style="font-size:1.7rem; font-weight:bold;">{{$value->gpo}} {{$value->nombre}}</span>
                            </span>
                        
                            <!-- ---------------------- Sabores  ----------------------- -->
                            @if($num > '1')
                                <select class="producto" wire:model="sabor" style="margin-left: 1rem;" name="sab_{{$value->id}}">
                                    @foreach($sabores as $i)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="sab_{{$value->id}}" value="">
                            @endif
                            
                            <!-- ---------------------- Precio  ----------------------- -->
                            <span style="font-size:2rem; color:var(--cuadroTotal); float: right;">
                                $ {{$precio}}
                                @if( session("usr_estatus") == 'act')
                                    <div style="font-size:1.3rem;text-align:right;font-style:italic;"> $ {{$value->precioreg}}</div> 
                                @endif
                                $ <b><span class="CalculadoraSubtotal" id="subtot_{{$value->id}}">0</span></b><br>
                            </span>
                        </div>
    
                        <span style="font-size:1.5rem;color:black;">{{$value->name}}</span>
                        
                    </div> 
    
                    <div style="margin-top:0.5rem;">
                        <!--- ------------------------------- Seccion DE INFO OCULTA ------------------------------------------------>
                        <div id="sale_{{$key}}{{$value->id}}" style='display:none; font-size:0.9rem; margin-top:1rem;'> 
                            <div>
                                <div style='background-color:#A7A7A7; color:white;font-size:1.5rem;'>
                                    <b> &nbsp; Productor:</b>  <a href='../productores.php#{{$value->proveedor}}' style='color: inherit; text-decoration: none' target='new'>{{$value->proveedor}}</a>
                                </div>
                                <div style="overflow:auto;">
                                    <img src='http://coopelatina.org/sistema/img/productos/Aguacate_Barzon.jpeg' style='width:150px;margin:15px; float:left;'><br>
                                    <p style="font-size:1.5rem;"> {{$value->descripcion}}<br> <i>Presentación: <b>{{$value->presentacion}}</b></i> </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!--- ------------------------------- Seccion INPUT DE PEDIDO ------------------------------------------------>
                    <div style="display:flex; ">  <!-- com1, com2, com12, comid -->
                        <div style="width:120px; display:flex;"> 
                                <input type='number' class='producto' name="com1_{{$value->id}}" id="com1_{{$value->id}}" onkeyup="subtotal('{{$value->id}}','{{$value->entrega}}','{{$precio}}');" onchange="subtotal('{{$value->id}}','{{$value->entrega}}','{{$precio}}');"    min="0" step="1" placeholder="{{$com1text}}" {{$com1act}}> 
                                <div class="CantSubtotal" style="margin-left:3px;">10</div>
                        </div>
                        
    
                        <div style="margin-left:0.5rem; width:120px; display:flex;">
                            @if($value->entrega == 'comid')
                                <input type='number' class='producto' name="com3_{{$value->id}}" id="com3_{{$value->id}}" readonly>
                                <input type="hidden" id="com2_{{$value->id}}">
                            @else
                                <input type='number' class='producto' name="com2_{{$value->id}}" id="com2_{{$value->id}}" onkeyup="subtotal('{{$value->id}}','{{$value->entrega}}','{{$precio}}');" onchange="subtotal('{{$value->id}}','{{$value->entrega}}','{{$precio}}');"    min="0" step="1"  placeholder="{{$com2text}}" {{$com2act}} >  
                            @endif
                            <div class="CantSubtotal" style="margin-left:3px;">19</div>
                        </div>
    
                        <div>
                            <a href="#" style="nolink"><i class="fas fa-cart-plus" style="font-size:2.4rem; color:black;"> </i>  </a>
                            <a href="#" style="nolink"><i class="fas fa-trash" style="font-size:2.4rem; color:black;margin-left:.5rem;"> </i>  </a>
                        </div>
                    </div>
                </div class="recuadro">
            @endforeach 
        </form>
    @endsection
--}}

