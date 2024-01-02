<div>
    <h1>Prouctos de ocasión</h1>

    <!-- ----------------------- Switch de Estado ------------------------ -->
    <div>
        <label>Pedidos de ocasión</label><br>
        <label class="switch">
            <input type="checkbox" wire:model="EnOcasion" wire:click="CambiaEstado">
            <div class="slider round"></div> 
        </label>  
        @if(session('ocasion')=='1')
            <span style="color:darkgreen;">Sí se está ofreciendo productos de ocasión.</span> 
        @else
            <span style="color:gray;">No se ofrecen productos de ocasión.</span> <!--span style="color:gray;"> Abasto Desactivo</span-->
        @endif
    </div>
</div>
