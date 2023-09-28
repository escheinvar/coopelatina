<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\productosModel;

class PrepedidosLivewireComponent extends Component
{
    #public $sabor;
    public $fin0, $fin1, $fin2, $fin3, $fin4, $fin5, $fin6, $fin7, $fin8, $fin9;
    public $fin10, $fin11, $fin12, $fin13, $fin14, $fin15, $fin16, $fin17, $fin18, $fin19;
    public $fin20, $fin21, $fin22, $fin23, $fin24, $fin25, $fin26, $fin27, $fin28, $fin29;
    public $fin30, $fin31, $fin32, $fin33, $fin34, $fin35, $fin36, $fin37, $fin38, $fin39;
    public $fin40, $fin41, $fin42, $fin43, $fin44, $fin45, $fin46, $fin47, $fin48, $fin49;
    public $fin50, $fin51, $fin52, $fin53, $fin54, $fin55, $fin56, $fin57, $fin58, $fin59;
    public $fin60, $fin61, $fin62, $fin63, $fin64, $fin65, $fin66, $fin67, $fin68, $fin69;
    public $fin70, $fin71, $fin72, $fin73, $fin74, $fin75, $fin76, $fin77, $fin78, $fin79;
    public $fin80, $fin81, $fin82, $fin83, $fin84, $fin85, $fin86, $fin87, $fin88, $fin89;
    public $fin90, $fin91, $fin92, $fin93, $fin94, $fin95, $fin96, $fin97, $fin98, $fin99;
    public $fin100, $fin101, $fin102, $fin103, $fin104, $fin105, $fin106, $fin107, $fin108, $fin109;
    public $fin110, $fin111, $fin112, $fin113, $fin114, $fin115, $fin116, $fin117, $fin118, $fin119;
    public $fin120, $fin121, $fin122, $fin123, $fin124, $fin125, $fin126, $fin127, $fin128, $fin129;
    public $fin130, $fin131, $fin132, $fin133, $fin134, $fin135, $fin136, $fin137, $fin138, $fin139;
    public $fin140, $fin141, $fin142, $fin143, $fin144, $fin145, $fin146, $fin147, $fin148, $fin149;
    /*public $fin150, $fin151, $fin152, $fin153, $fin154, $fin155, $fin156, $fin157, $fin158, $fin159;
    public $fin160, $fin161, $fin162, $fin163, $fin164, $fin165, $fin166, $fin167, $fin168, $fin169;
    public $fin170, $fin171, $fin172, $fin173, $fin174, $fin175, $fin176, $fin177, $fin178, $fin179;
    public $fin180, $fin181, $fin182, $fin183, $fin184, $fin185, $fin186, $fin187, $fin188, $fin189;
    public $fin190, $fin191, $fin192, $fin193, $fin194, $fin195, $fin196, $fin197, $fin198, $fin199;
    public $fin200, $fin201, $fin202, $fin203, $fin204, $fin205, $fin206, $fin207, $fin208, $fin209;
    public $fin210, $fin211, $fin212, $fin213, $fin214, $fin215, $fin216, $fin217, $fin218, $fin219;
    public $fin220, $fin221, $fin222, $fin223, $fin224, $fin225, $fin226, $fin227, $fin228, $fin229;
    public $fin230, $fin231, $fin232, $fin233, $fin234, $fin235, $fin236, $fin237, $fin238, $fin239;
    public $fin240, $fin241, $fin242, $fin243, $fin244, $fin245, $fin246, $fin247, $fin248, $fin249;
    public $fin250, $fin251, $fin252, $fin253, $fin254, $fin255, $fin256, $fin257, $fin258, $fin259;
    public $fin260, $fin261, $fin262, $fin263, $fin264, $fin265, $fin266, $fin267, $fin268, $fin269;
    */
    public $jaja,$a,$b;

    public function cargar($id) {
        $ja = 'this->fin'.$id;
        #$ja = $this->fin5;
        #$ja = '6';
        dd("fin",$id,$ja,${$ja});
        #$this->request->session('prep')->put($id, '5');
        #session(['prep'=> array_push()[
        #    $id=>$ja,
        #]  ]);#
       
    }
  
    public function render()
    {
        
        $todo = productosModel::where('activo','1')
            ->where('entrega','not like','no')
            ->orderBy('gpo')
            ->get();
        return view('livewire.prepedidos-livewire-component',compact('todo'));
    }
}
