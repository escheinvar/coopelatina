<div>

    <style>
        .bla{position: sticky; top: 500;}
    </style>
<form method="post">
    @csrf
    <div style="display:flex;float:right;">
        <div style="font-size:3rem; float:right;">
            Total $ <span id="CalculadoraTotal">0</span> 
        </div>
        &nbsp; &nbsp;
        <div style="float: right;">            
            <button class="btn btn-primary" type="submit" style="display:inline-block;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                Hacer Prepedido
            </button>
            
        </div>
    </div>
    <table class="table table-striped table-hover" style="width:90%">
        <thead>
        <tr>
            <th scope="col">Producto</th>
            <th scope="col">Precio {{session("usr_estatus")}}</th>
            <th scope="col">Pedido</th>
            <th scope="col">Total</th>
        </tr>
        </thead>
        <tbody>
            @foreach($todo as $key=>$value)
                <?php
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
                    }
                ?>
                @foreach($sabores as $sabor)
                    <tr>
                        <td>
                            <span onclick="VerNoVer('{{$key}}','{{$value->id}}{{$sabor}}');";>
                                <!-- ---------------------- Grupo y nonmbre (desplegables)  ----------------------- -->
                                <i class='fa fa-info-circle' style='font-size:18px;color:#BDBDBD' ></i>
                                <span style="font-size:1.7rem; font-weight:bold;">{{$value->gpo}} {{$value->nombre}} 
                                    <span style="color:gray">{{$sabor}}</span>
                                </span>

                                <!--- ------------------------------- Seccion DE INFO OCULTA ------------------------------------------------>
                                <div id="sale_{{$key}}{{$value->id}}{{$sabor}}" style='display:none; font-size:0.9rem; margin-top:1rem;'> 
                                    <div style="overflow:auto;">
                                        <img src='http://coopelatina.org/sistema/img/productos/Aguacate_Barzon.jpeg' style='width:150px;margin:15px; float:left;'><br>
                                        <p style="font-size:1.5rem;"> {{$value->descripcion}}<br> <i>Presentación: <b>{{$value->presentacion}}</b></i> </p>
                                        @if( session("usr_estatus") == 'act')
                                        <div style="font-size:1.3rem;font-style:italic;display:inline-block;">Act $ {{$value->precioact}} </div>  &nbsp; &nbsp;
                                        <div style="font-size:1.3rem;font-style:italic;display:inline-block;">Reg <b>$ {{$value->precioreg}}</b></div>   &nbsp; &nbsp;
                                        <div style="font-size:1.3rem;font-style:italic;display:inline-block;">Pub $ {{$value->preciopub}}</div> 
                                        @endif
                                    </div>
                                    <div style='background-color:#A7A7A7; color:white;font-size:1.5rem;'>
                                        <b> &nbsp; Productor:</b>  <a href='../productores.php#{{$value->proveedor}}' style='color: inherit; text-decoration: none' target='new'>{{$value->proveedor}}</a>
                                    </div>
                                </div>
                            </span>
                        </td>

                        <td>
                            <!-- ---------------------- Precio   ----------------------- -->
                            <span style="font-size:1.7rem; color:var(--cuadroTotal);">
                                $ {{$precio}}
                                @if($value->entrega=='comid')
                                <span style="color:gray;"> x2</span>
                                @endif
                            </span>
                        </td>

                        <td>
                            <div style="display:flex;">  <!-- com1, com2, com12, comid -->
                                <div style="width:120px; display:flex;"> 
                                    <!-- ------- Primer celda de pedido com1 ---------- -->
                                    <input type='number' class='producto' name="com1_{{$value->id}}{{$sabor}}" id="com1_{{$value->id}}{{$sabor}}" onkeyup="subtotal('{{$value->id}}{{$sabor}}','{{$value->entrega}}','{{$precio}}');" onchange="subtotal('{{$value->id}}{{$sabor}}','{{$value->entrega}}','{{$precio}}');"    min="0" step="1"  {{$com1act}}> <!--placeholder="{{$com1text}}"-->
                                </div>
                
                                <div style="margin-left:0.5rem; width:120px; display:flex;">
                                    <!-- ------- Segund celda de pedido com2 ---------- -->
                                    @if($value->entrega == 'comid')
                                        <input type='number' class='producto' name="com2_{{$value->id}}{{$sabor}}" id="com2_{{$value->id}}{{$sabor}}" readonly>
                                    @else
                                        <input type='number' class='producto' name="com2_{{$value->id}}{{$sabor}}" id="com2_{{$value->id}}{{$sabor}}" onkeyup="subtotal('{{$value->id}}{{$sabor}}','{{$value->entrega}}','{{$precio}}');" onchange="subtotal('{{$value->id}}{{$sabor}}','{{$value->entrega}}','{{$precio}}');"    min="0" step="1"   {{$com2act}} >  <!-- placeholder="{{$com2text}}"-->
                                    @endif
                                </div>     
                            </div>   
                        </td>

                        <td>
                            <!-- ----------------------  Subtotal  ----------------------- -->
                            <span style="font-size:2rem; color:var(--cuadroTotal); float: right;">
                                $ <b><span class="CalculadoraSubtotal" id="subtot_{{$value->id}}{{$sabor}}">0</span></b><br>
                            </span>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</form>

</div>
