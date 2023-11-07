<?php

namespace App\Http\Livewire\Admon;

use App\Models\AbastosModel;
use App\Models\EstadosModel;
use Livewire\Component;
use App\Models\ProductosModel;
use Illuminate\Support\Facades\DB;

class ListasAbastoLiveComponent extends Component
{
    public $SelEncar='%', $SelProd="%", $switch;
    public $orden='responsable', $sentido='asc';
    #public $ValorPed1;
    public $petitCmte=['root','teso'];
    
   

    public function orden($campo){  
        $this->orden = $campo;
        if($this->sentido == 'asc'){
            $this->sentido = 'desc';
        }else{
            $this->sentido = 'asc';
        }        
    }
    
    public function SwitchListasAbasto(){
        if(session('ListasAbasto') =='1'){
            $this->switch='';
            session(['ListasAbasto'=>'']);
            EstadosModel::where('edo_name','ListasDeAbasto')->update(['edo_edo'=>'']);
        }else{
            $this->switch='1';
            session(['ListasAbasto'=>'1']);
            EstadosModel::where('edo_name','ListasDeAbasto')->update(['edo_edo'=>'1']);
        }
    }

    public function mount(){
        $this->switch=session('ListasAbasto');
    }

    public function confirmaAbasto($com, $prodId,$sabor, $ValorPed, $ValorTien){
        AbastosModel::create([
            'aba_anio'=>session('ProxCom2date')['anio'],
            'aba_mes'=>session('ProxCom2date')['mes'],
            'aba_com'=>$com,
            'aba_prodid'=>$prodId,
            'aba_prodsabor'=>$sabor,
            'aba_producto'=>$com.':'.$prodId.'@'.$sabor,
            'aba_listas'=>'1',
            'aba_listas_cantpeds'=>$ValorPed,
            'aba_listas_canttien'=>$ValorTien,
        ]);
        #$this->emit('alerta','Guardado','Exitosamente');
    }

    public function render() {
        ########### Lista de productos
        $prods=ProductosModel::where('activo','1')
            ->where('responsable','like',$this->SelEncar)
            ->where('proveedor','like',$this->SelProd)
            ->orderBy($this->orden,$this->sentido)
            ->get();
        ########### Lista de encargados y de productores 
        $encargados=ProductosModel::select('responsable')->where('activo','1')->distinct()->orderBy('responsable','asc')->get();
        $productores=ProductosModel::select('proveedor')->where('activo','1')->distinct()->orderBy('proveedor','asc')->get();

        ######### GENERA NUEVA TABLA CON TOTAL DE PEDIDOS (Y DE TIEND) PARA LAS DOS ENTREGAS PARA EL AÑO Y MES INDICADO
        #########                       total,    prod,        aba_listas, aba_listas_cantpeds, aba_listas_canttien.
        #########                         3,    com1:30@SaborA,    0,               0,                   0
        ######### Excluye pedidos y folios inactivos,    excluye no pagados
        $mes=session('ProxCom2date')['mes']; 
        $anio=session('ProxCom2date')['anio']; 

        $pedidos=DB::select(
            "SELECT ped_producto, SUM(ped_cant) AS total, aba_listas  FROM folios_prods 
            JOIN folios ON folios_prods.ped_folio=folios.fol_id
            left JOIN (select * from abastos where aba_anio=$anio and aba_mes=$mes) as abastos2 ON CONCAT(ped_entrega,':',ped_prodid,'@',ped_prodvar) = abastos2.aba_producto
            WHERE ped_act='1' AND  fol_act='1' AND fol_edo='3' AND fol_anio=$anio AND fol_mes=$mes  AND fol_usrid > '0'
            GROUP BY ped_producto, aba_listas;");
        $tienda=DB::select(
            "SELECT ped_producto, SUM(ped_cant) AS total, aba_listas  FROM folios_prods 
            JOIN folios ON folios_prods.ped_folio=folios.fol_id
            left JOIN (select * from abastos where aba_anio=$anio and aba_mes=$mes) as abastos2 ON CONCAT(ped_entrega,':',ped_prodid,'@',ped_prodvar) = abastos2.aba_producto
            WHERE ped_act='1' AND  fol_act='1' AND fol_edo='3' AND fol_anio=$anio AND fol_mes=$mes  AND fol_usrid = '0'
            GROUP BY ped_producto, aba_listas;");

        #dd('prods=catDeprods',$prods,'pedidos=[total,prod] = 5,com1:24@SaborA',$pedidos,'tienda=[total,prod] = 5,com1:24@SaborA',$tienda,     'encargados=listaUnicosEncargs',$encargados,'productores=ListaUnicosProductores',$productores);
        return view('livewire.admon.listas-abasto-live-component',['productos'=>$prods, 'pedidos'=>$pedidos, 'tienda'=>$tienda, 'encargados'=>$encargados,'productores'=>$productores]);
    }
}
