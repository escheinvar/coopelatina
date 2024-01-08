<div>
    <div class="container-fluid">
        <h1>Entregas <i class='fas fa-people-carry' style="font-size: 110%"></i> </h1>

        <div>
            Hoy es {{session('arraySemana')[date('w')]}} {{date("j")}} de {{session('arrayMeses')[date('n')]}}<br>
            @if(session('ProximaCom')[0]=='com1') Primer @else Segunda @endif entrega del mes ({{(session('ProximaCom'))[0]}}   )
        </div>


        <div>
            <input type="text" placeholder="Buscar por nombre..." wire:model="BuscaNombre">
        </div>

        <div class="form-check" display="display: inline-block">
            <input class="form-check-input" type="radio" value='3' wire:model="estado">
            <label class="form-check-label" for="flexRadioDefault1">
                Por entregar
            </label>
        </div>

        <div class="form-check" style="display: inline-block;">
            <input class="form-check-input" type="radio" value='1' wire:model="estado">
            <label class="form-check-label" for="flexRadioDefault2">
                Ya entregados
            </label>
        </div>

        <div> Hay {{$folios->count()}} pedidos para entregar</div>
        
        @foreach ($folios as $folio)
            <div>
                <!--################################################### Cabecera ###########################################-->
                <div class="my-3 p-3" style="display:flex; cursor:pointer;background-color:rgb(191, 225, 254);" onclick="VerNoVer('folio','{{$folio->fol_id}}');">
                    <div>
                        {{$folio->nombre}}  {{$folio->ap_pat}} Folio {{ str_pad($folio->fol_id,4,"0",STR_PAD_LEFT)}}
                    </div> 
                </div>
                <!--################################################### Cuerpo ###########################################-->
                <div class="my-2 p-2" id="sale_folio{{$folio->fol_id}}" style="display:none;">
                    @foreach($pedidos as $ped)
                        @if($folio->fol_id == $ped->ped_folio)
                            <div class="row" style="min-height: 80px;">
                                <div style="display:flex;">

                                    <!-- --------------------- Input cantidad ----------------------- -->
                                    <div>
                                        @if( $ped->ped_entregado =='0') 
                                            <input type="number" 
                                                name="folio{{$folio->fol_id}}+{{$ped->ped_producto}}" 
                                                value="{{$ped->ped_cant - $ped->ped_cantentregada}}" 
                                                style="width:50px;text-align:center;" 
                                                onclick="OcultaTranfiere();"
                                                min="0" max="{{$ped->ped_cant- $ped->ped_cantentregada}}">
                                        @else
                                            <div style="width:50px;text-align:center;">
                                                {{$ped->ped_cantentregada}}
                                                @if($ped->ped_transfiere == '1')<span style="color:orange;"><ch>tr</ch>{{$ped->ped_cant - $ped->ped_cantentregada}}</span> @endif
                                            </div> 
                                        @endif
                                    </div>

                                    <!-- ------------------- Solicitado original --------------------- -->
                                    <div class="mx-3" style="width:20px;">                                    
                                        {{$ped->ped_cant}} 
                                    </div>

                                    <!-- -------- Nombre, Sabor, Presentación y botonse --------------- -->
                                    <div style="">
                                        <div>
                                            <span style="font-weight: bold;">{{$ped->ped_prod}} <span style="color:gray;">{{$ped->ped_prodvar}}</span></span> <span style="font-size:80%">{{$ped->ped_prodpresenta}}</span>
                                            <span style="font-size: 50%;">{{$ped->ped_producto}}</span>
                                        </div>
                                        <div>   
                                            @if($ped->aba_faltante =='1' AND $ped->ped_entregado =='0')
                                                <div class="BotonesEspeciales" style="float: right; display:block;">
                                                    <button type="button" wire:click="transfiere('{{$ped->ped_id}}')" class="btn btn-success btn-xs"> Transferir</button>  &nbsp; 
                                                    <!--button type="button" class="btn btn-secondary btn-xs"> Devolver</button-->
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @if($folio->fol_edo == '3')
                        <button type="submit" name="Folio" value="{{$folio->fol_id}}" class="btn btn-danger btn-sm"> Entregar</button>
                    @endif
                </div>
            </div>
        @endforeach
        
    </div>
    


    @push('scripts')
        <script type="text/javascript">  
            //--------------------------------  Calcula subtotal  por cada producto
            function OcultaTranfiere(){
                cadaUno=document.getElementsByClassName('BotonesEspeciales');
                var x=0; var tot=0;
                for (i=0, max=cadaUno.length; i<max; i++) {
                    cadaUno[i].style.display = "none"
                }
                //console.log('a',tot,typeof(total))
            }
        </script>
    @endpush
</div>
