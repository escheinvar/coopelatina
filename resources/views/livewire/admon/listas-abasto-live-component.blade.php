<div>
  
  <h1>Listas de abasto para el mes de {{session('arrayMeses')[session('ProxCom2date')['mes']]}}</h1>
  @include('plantillas.MarcadorDeEstado')

  <div class="row">
    <!-- ----------------------- Switch de Estado ------------------------ -->
    @if( in_array(auth()->user()->priv, $petitCmte) AND session('EnPagos') == '' AND session('EnPedido') =='0')
      <div>
        <label> Listas de Abasto</label><br>
        <label class="switch">
          <input type="checkbox" wire:model="switch" wire:click="SwitchListasAbasto()">
          <div class="slider round"></div> 
        </label>  
        @if(session('ListasAbasto')=='1')
          <span style="color:darkgreen;">Listas de abasto activas.</span> <!--span style="color:gray;"> Abasto Desactivo</span-->
        @else
          <span style="color:gray;">Listas de abasto inactivas.</span> <!--span style="color:darkgreen;"> Abasto Activo.</span-->
        @endif
      </div>
    @endif
  </div>
  
 


  <div class="row">
    <!-- -----------------------------Filtra por Encargado--------------------------------------- -->
    <div class="form-group col-md-3">
      <label >Encargado:</label>
      <select class="form-control @error('SelEncar')error @enderror" wire:model="SelEncar" value="{{old('SelEncar')}}">
        <option value="%" >Todos</option>
        @foreach($encargados as $i)
          <option value="{{$i->responsable}}">{{$i->responsable}}</option>  
        @endforeach
      </select>
      @error('SelEncar')<error> {{$message}}</error> @enderror
    </div>

    <!-- ----------------------------- Filtra por  Productor --------------------------------------- -->
    <div class="form-group col-md-3">
      <label >Productor:</label>
      <select class="form-control @error('SelProd')error @enderror" wire:model="SelProd" value="{{old('SelProd')}}">
        <option value="%" >Todos</option>
        @foreach($productores as $i)
          <option value="{{$i->proveedor}}">{{$i->proveedor}}</option>  
        @endforeach
      </select>
      @error('SelProd')<error> {{$message}}</error> @enderror
    </div>
  </div>
  
  @if(session('ListasAbasto')=='') <h3 style="color:gray;">-- Listas de abasto inactivas --</h3>@endif
  <!-- ----------------------------- INICIA TABLA --------------------------------------- -->
  <div class="row">
    <div class="col-12">
      <table class="table table-striped table-hover">
        <thead>
          <!-- ----------------------------- CABECERA DE TABLA ---------------------------------------- -->
          <tr>
            <th>
              <div style="text-align: center;color:red;">!Atención¡: Guardar antes de confirmar cualquier pedido</div>
            </th>
          </tr> 
          <tr class="sticky-top">
            
            <th>
              <div class="col-lg-4 col-md-4 col-sm-2">
                <span wire:click="orden('responsable')" style="cursor:pointer;">Encargado</span> /
                <span wire:click="orden('gpo')" style="cursor:pointer;">Producto</span>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-1">
                Productor
              </div>
              <div class="col-lg-1 col-md-1 col-sm-1">
                <span wire:click="orden('entrega')" style="cursor:pointer;">Entrega</span> <ch>Min</ch>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2">
                Peds1[Tien1]
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2">
                Peds2[Tien2]
              </div>
              <div>
                <!--##################################### BOTÓN DE GUARDAR ##################################################### -->
                <button type="submit" class="btn btn-danger" @if(session('ListasAbasto')=='')  disabled @endif> Guardar </button>
              </div>
            </th>
          </tr>
        </thead>
        
        <tbody>
          @foreach ($productos as $prod)
            <?php
              #### Genera vector de sabores   
              $sabores=explode(',', $prod->variantes);
              
              ##### Oculta los campos para que solo se edite quien está autorizado
              $DesactivaIn=""; $DesactivaBu="";
              if(auth()->user()->usr != $prod->responsable and !in_array(auth()->user()->priv, $petitCmte)){
                $DesactivaIn="readonly";
                $DesactivaBu="disabled";
              }
              if(session('ListasAbasto')==''){
                $DesactivaIn="readonly";
                $DesactivaBu="disabled";
              }
            ?>
            @foreach($sabores as $sabor)
              <?php 
                $ValorPed1='0';$ValorTien1='0';$AbaListas1='0';
                $ValorPed2='0';$ValorTien2='0';$AbaListas2='0';
                ##### Para cada producto-sabor de BD-productos, busca, para cada com si hay pedidos y lo guarda en $ValorPed
                foreach($pedidos as $i){ 
                  if( preg_match("/com1:$prod->id@$sabor/",  $i->ped_producto) ){ 
                    $ValorPed1=$i->total; 
                    if($i->aba_listas=='1'){$AbaListas1++;} 
                  }else if( preg_match("/com2:$prod->id@$sabor/",  $i->ped_producto) ){ 
                    $ValorPed2=$i->total; 
                    if($i->aba_listas=='1'){$AbaListas2++;} 
                  } 
                }
                ##### Para cada producto-sabor de BD-productos, busca, para cada com si hay pedidos
                foreach($tienda as $i){
                  if( preg_match("/com1:$prod->id@$sabor/",  $i->ped_producto) ){ 
                    $ValorTien1=$i->total; 
                    if($i->aba_listas=='1'){$AbaListas1++;} 
                  }else if( preg_match("/com2:$prod->id@$sabor/",  $i->ped_producto) ){
                      $ValorTien2=$i->total; 
                      if($i->aba_listas=='1'){$AbaListas2++;} 
                    } 
                }
                $ValorTot1=$ValorPed1+$ValorTien1; 
                $ValorTot2=$ValorPed2+$ValorTien2;
              ?>
              <tr>
                <td>
                  <div class="row"> 
                    <!--############################## MUESTRA RESPONSABLE, PRODUCTO Y PROVEEDO ###############################-->
                    <div class="col-lg-4 col-md-4 col-sm-12">
                      {{substr($prod->responsable, 0, 4)}} &nbsp; <b>{{$prod->gpo}} {{$prod->nombre}}</b> <i>{{$sabor}}</i> <small> {{$prod->presentacion}}</small> ({{$prod->id}})
                      <BR><ch>{{$prod->id.'@'}}{{$sabor}}</ch>
                    </div>

                    <!--############################## MUESTRA PRODUCTOR ###############################-->
                    <div class="col-lg-2 col-md-2 col-sm-12">
                      {{$prod->proveedor}}
                    </div>
                    
                    <!--############################## MUESTRA TIPO DE ENTREGA ###############################-->
                    <div class="col-lg-1 col-md-1 col-sm-12">
                      {{$prod->entrega}}
                      @if($prod->mintipo =='1')
                        <ch>{{$prod->min}}</ch>
                      @endif
                    </div>

                    <!--############################## Campo de primer entrega ###############################-->
                    <!--############################## Campo de primer entrega ###############################-->
                    <!--############################## Campo de primer entrega ###############################-->
                    <div class="col-lg-2 col-md-2 col-sm-3" style="display:flex;">
                      @if(in_array( $prod->entrega, ['com1','comid','com12','no']))

                        <!-- ------ Pedidos de comanda ------ -->
                        <div style="width:35px; text-align:right; padding:0.5rem;">
                            <span id="com1:ped_{{$prod->id}}@-{{$sabor}}" style="font-weight:bold;"> {{$ValorPed1}} </span>
                        </div>

                        <!-- ------ Pedidos para tienda ------ -->
                        <div style="width:90px;">                              
                          @if($AbaListas1 =='0' OR is_null($AbaListas1))
                            <input type='number' 
                              value="{{$ValorTien1}}" 
                              onchange="subtotal('1','{{$prod->id}}','{{$sabor}}')" class='producto' id="com1:tien_{{$prod->id}}@-{{$sabor}}" name="com1:tien_{{$prod->id}}@-{{$sabor}}"  
                              min="0" step="1" {{$DesactivaIn}}>
                          @else
                            <div style='width:70px;text-align:center;font-weight:bold;padding:4px;'> {{$ValorTien1}}</div>
                            <input type="hidden" value="{{$ValorTien1}}"  id="com1:tien_{{$prod->id}}@-{{$sabor}}">
                          @endif
                        </div>

                        <!-- ------ Total de pedidos ------ -->
                        <div id="com1:tot_{{$prod->id}}@-{{$sabor}}" style="width:35px; text-align:left; padding:0.5rem;color:gray;">
                          ={{intval($ValorTot1)}}
                        </div>

                        <!-- ------ Boton de confirmar ------ -->
                        <div style="width:130px;">
                          @if($AbaListas1=='0' AND ($ValorTot1 > '0') )
                            <button type="button" 
                              id="confirma1_{{$prod->id}}@-{{$sabor}}"
                              style="display:block;float: right;" 
                              class="btn btn-primary" 
                              wire:click="confirmaAbasto('com1','{{$prod->id}}','{{$sabor}}','{{intval($ValorPed1)}}','{{intval($ValorTien1)}}')" 
                              {{$DesactivaBu}}> Confir. 1</button>
                          @endif
                        </div>
                      @endif
                    </div>

                    <!--############################## Campo de SEGUNDA entrega ###############################-->
                    <!--############################## Campo de SEGUNDA entrega ###############################-->
                    <!--############################## Campo de SEGUNDA entrega ###############################-->
                    <div class="col-lg-2 col-md-2 col-sm-3" style="display:flex;">
                      @if(in_array( $prod->entrega, ['com2','comid','com12','no']))
                        <!-- ------ Pedidos de comanda ------ -->
                        <div style="width:35px; text-align:right; padding:0.5rem;">
                            <span id="com2:ped_{{$prod->id}}@-{{$sabor}}" style="font-weight:bold;"> {{$ValorPed2}} </span>
                        </div>

                        <!-- ------ Pedidos para tienda ------ -->
                        <div style="width:90px;">                              
                          @if($AbaListas2 =='0' OR is_null($AbaListas2))
                            <input type='number' value="{{$ValorTien2}}" 
                              onchange="subtotal('2','{{$prod->id}}','{{$sabor}}')" 
                              class='producto' 
                              id="com2:tien_{{$prod->id}}@-{{$sabor}}" 
                              name="com2:tien_{{$prod->id}}@-{{$sabor}}"  
                              min="0" step="1" {{$DesactivaIn}}>
                          @else
                            <div style='width:70px;text-align:center;font-weight:bold;padding:4px;'> {{$ValorTien2}}</div>
                            <input type="hidden" value="{{$ValorTien2}}"  id="com2:tien_{{$prod->id}}@-{{$sabor}}">
                          @endif
                        </div>

                        <!-- ------ Total de pedidos ------ -->
                        <div id="com2:tot_{{$prod->id}}@-{{$sabor}}" style="width:35px; text-align:left; padding:0.5rem;color:gray;">
                          ={{intval($ValorTot2)}}
                        </div>
                        
                        <!-- ------ Boton de confirmar ------ -->
                        <div style="width:130px; text-align:right;">
                          @if($AbaListas2=='0' AND $ValorTot2 > '0' )
                            <button type="button" 
                              id="confirma2_{{$prod->id}}@-{{$sabor}}" 
                              style="display:block; float:right; "
                              class="btn btn-primary" 
                              wire:click="confirmaAbasto('com2','{{$prod->id}}','{{$sabor}}','{{intval($ValorPed2)}}','{{intval($ValorTien2)}}')" 
                              {{$DesactivaBu}}>Confir. 2</button>
                          @endif

                          
                        </div>
                        
                      @endif
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  


  @push('scripts')
    <script>
      function subtotal(com,id,pvar) {
        var comPed = document.getElementById('com'+com+':ped_'+id+'@-'+pvar).innerHTML
        var compTie= document.getElementById('com'+com+':tien_'+id+'@-'+pvar).value
        if(comPed == null  || comPed == undefined  || comPed == "")  {comPed="0";}
        if(compTie == null  || compTie == undefined  || compTie == "")  {compTie="0";}
        //-------------------------------- Cacluca el subtotal y lo manda al <span id=subtotal>
        //var subtotal =  parseFloat(comPed) + parseFloat(compTie);
        var subtotal =  Number(comPed) + Number(compTie);
        document.getElementById('com'+com+':tot_'+id+'@-'+pvar).innerHTML = '='+subtotal
        //############ Muestra u oculta botones
        document.getElementById('confirma'+com+'_'+id+'@-'+pvar).style.display = "none"
        //console.log('a',subtotal, typeof(subtotal))
      }
    </script>
  @endpush
</div>
