<div>
  
  <h1>Listas de abasto para el mes de {{session('arrayMeses')[session('ProxCom2date')['mes']]}}</h1>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      @if(session('EnPagos')=='1')
        <span style="color:orange;">Aún se siguen registrando pagos, todavía no podemos realizar la lista de abasto.</span>
      @else
        <span style="color:darkgreen;">Ya finalizamos el registro de pagos. Ya puedes cargar la lista de abasto.</span>
      @endif
    </div>
    <br><br>
    
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
  




  <!-- ----------------------------- INICIA TABLA --------------------------------------- -->
  

  <div class="row">
    <div class="sticky-top" style="background-color: white; padding: 3rem; text-align:right; opacity:1;">
      <button type="submit" class="btn btn-success" style="width:300px;"> Guardar </button>
    </div>
    <div class="col-12">
      Nota: Solo se muestra para captura, los productos con venta en tienda.
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>
              <div class="col-lg-5 col-md-5 col-sm-2">
                <span wire:click="orden('responsable')" style="cursor:pointer;">Encar.</span>  / 
                <span wire:click="orden('gpo')" style="cursor:pointer;">Producto</span> / 
                <span wire:click="orden('proveedor')" style="cursor:pointer;">Productor</span>
              </div>
              <div class="col-lg-1 col-md-1 col-sm-1">
                <span wire:click="orden('entrega')" style="cursor:pointer;">Entrega</span> / 
                Min
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2">
                Peds + tien 1
              </div>
              <div class="col-lg-2 col-md-2 col-sm-2">
                Peds + tien 2
              </div>
            </th>
          </tr>
        </thead>

        <tbody>
          @foreach ($prods as $prod)
            <?php
              #### Genera vector de sabores   
              $sabores=explode(',', $prod->variantes);
              $num=count($sabores);
              ### Quita palabras repetidas de nombre (cuando ya está en gpo)
              $prod->nombre = preg_replace("/$prod->gpo/i","",$prod->nombre);
              ### Determina comportamiento según entrega:
              
              ##### Oculta según la entrega programada
              if($prod->entrega == 'com1'){         $com1act="";          $com2act="disabled";
              }else if($prod->entrega == 'com2'){   $com1act="disabled";  $com2act=""; 
              }else if($prod->entrega =='com12'){   $com1act="";          $com2act=""; 
              }else if($prod->entrega == 'comid'){  $com1act="";          $com2act="disabled";
              }else if($prod->entrega == 'no' && $prod->venta=='si'){$com1act="";$com2act=""; }
              ###### además, oculta si no tle compete al usuario (si no es responsable)
              $petitCmte=['root','teso'];
              if(auth()->user()->usr != $prod->responsable and !in_array(auth()->user()->priv, $petitCmte)){$com1act=$com2act="readonly";}
              
              if(session('EnPagos')=='1'){$com1act=$com2act="disabled";}
            ?>
            @foreach($sabores as $pvar) 
              <tr>
                <td>
                  <div class="row">    
                    <!-- ------------------------------ responsable, nombre del producto y Proveedor-------------------------- -->
                    <div class="col-lg-5 col-md-5 col-sm-12">
                      {{substr($prod->responsable, 0, 4)}} &nbsp; <b>{{$prod->gpo}} {{$prod->nombre}}</b> <i>{{$pvar}}</i> <small> {{$prod->presentacion}}</small> ({{$prod->id}})
                      <br>{{$prod->proveedor}} <br>
                    
                    </div>

                    <!-- ------------------------------ tipo com o tienda / minimo-------------------------- -->
                    <div class="col-lg-1 col-md-1 col-sm-12">
                      {{$prod->entrega}} <!--/ {{$prod->venta}}-->
                      <span style="border:1px solid rgb(158, 158, 158); background-color: rgb(176, 177, 160); color:white; height:50%;font-size:50%; margin:2px; border-radius:100%;padding:2px;">
                        3 
                    </span>
                    </div>
                    <?php
                      #### Busca el valor que corresponde a suma de pedidos o de tienda
                      $ValorPed1="";$ValorTien1="";
                      foreach($pedidos as $i){ if( preg_match("/com1:$prod->id@$pvar/",  $i->prod) ){ $ValorPed1=$i->total; } }
                      foreach($tienda as $i){ if( preg_match("/com1:$prod->id@$pvar/",  $i->prod) ){ $ValorTien1=$i->total; } }
                      $ValorPed2="";$ValorTien2="";
                      foreach($pedidos as $i){ if( preg_match("/com2:$prod->id@$pvar/",  $i->prod) ){ $ValorPed2=$i->total; } }
                      foreach($tienda as $i){ if( preg_match("/com2:$prod->id@$pvar/",  $i->prod) ){ $ValorTien2=$i->total; } }
                    ?>
                    <!-- ------------------------------com1 Primer entrega -------------------------- -->
                    <div class="col-lg-2 col-md-2 col-sm-3" style="display:flex;">
                      <!-- ------------com1 Pedidos de usuarios para com1 ---- -->
                      <div style="width:10%; text-align:right; padding:0.5rem;" id="com1:ped_{{$prod->id}}@-{{$pvar}}" >
                        {{$ValorPed1}}
                      </div>
                      <!-- ------------com1 tienda para com1 ---- -->
                      <div >
                        @if(session('EnPagos')=='0')
                          <input type='number' value="{{$ValorTien1}}" onload="subtotal('1','{{$prod->id}}','{{$pvar}}')" onchange="subtotal('1','{{$prod->id}}','{{$pvar}}')" class='producto' id="com1:tien_{{$prod->id}}@-{{$pvar}}" name="com1:tien_{{$prod->id}}@-{{$pvar}}"  min="0" step="1"   {{$com1act}}>
                        @endif
                      </div>
                      <!-- ------------com1 Total com1  ---- -->
                      <div style="width:20%; text-align:left; padding:0.5rem;color:gray;" id="com1:tot_{{$prod->id}}@-{{$pvar}}" >
                        <?php 
                          $pre1=intval($ValorPed1)+intval($ValorTien1);
                          if($pre1==0){$sale="";}else{$sale="=".$pre1;}; 
                        ?>
                        {{$sale}}
                      </div>
                    </div>

                    <!-- ------------------------------ Segunda entrega -------------------------- -->
                    <div class="col-lg-2 col-md-2 col-sm-3" style="display:flex; ">
                      <!-- ------------com2 Pedidos de usuarios para com2 ---- -->
                      <div style="width:10%; text-align:right; padding:0.5rem;" id="com2:ped_{{$prod->id}}@-{{$pvar}}" >
                        {{$ValorPed2}}
                      </div>
                      <!-- ------------com2 tienda para com2 ---- -->
                      <div style="">
                        @if(session('EnPagos')=='0')
                          <input type='number' class='producto' id="com2:tien_{{$prod->id}}@-{{$pvar}}" value="{{$ValorTien2}}"  name="com2:tien_{{$prod->id}}@-{{$pvar}}"   step="1"  {{$com2act}}>
                        @endif
                      </div>
                      <!-- ------------com2 Total com2  ---- -->
                      <div id="com2tot_{{$prod->id}}@-{{$pvar}}"  style="width:20%; text-align:left; padding:0.5rem;color:gray;">
                        <?php 
                          $pre2=intval($ValorPed2)+intval($ValorTien2);
                          if($pre2==0){$sale="";}else{$sale="=".$pre2;}; 
                        ?>
                        {{$sale}}
                      </div>
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
        //var subtotal =  (parseFloat(comPed) + parseFloat(compTie);
        var subtotal =  Number(comPed) + Number(compTie);
        document.getElementById('com'+com+':tot_'+id+'@-'+pvar).innerHTML = '='+subtotal

      }
    </script>
  @endpush
</div>
