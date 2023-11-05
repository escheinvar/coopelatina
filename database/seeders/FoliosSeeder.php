<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\FoliosModel;
use App\Models\FoliosProdsModel;
use App\Models\ProductosModel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FoliosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   ####################################################### CREA UN FOLIO
        $anio= date('Y', time() );
        $mes = date('m', time() );
        $name=User::inRandomOrder()->first();

        $events =[
            [
                'fol_edo'=>'4',
                'fol_anio'=>$anio,
                'fol_mes'=>$mes,
                'fol_usrid'=>$name->id,
                'fol_usr'=>$name->nombre,
            ],
        ];

        foreach($events as $i){  
            $folio=FoliosModel::create($i);
            #dd($folio->fol_id);
        }
        ####################################################### CREA Productos para el folio
        $prod=ProductosModel::inRandomOrder()->where('activo','1')->whereIn('entrega',['com1','com2','comid','com12'])->first();
        if($prod->entrega=='com12' OR $prod->entrega=='comid'){$entrega=['com1','com2'][array_rand([0,1],1)];}else{$entrega=$prod->entrega;}
        $numeros=['1','2','3','4','5','6'];
        $sabores=explode(",",$prod->variantes);
        $sabor=$sabores[array_rand($sabores,1)];

        $compra = [
            [
                'ped_folio'=>$folio->fol_id,
                'ped_producto'=>$entrega.":".$prod->id."@".$sabor,
                'ped_entrega'=>$entrega,
                'ped_prodid'=>$prod->id,
                'ped_prod'=>$prod->nombre,
                'ped_prodvar'=>$sabor,
                'ped_prodpresenta'=>$prod->presentacion,
                'ped_cant'=>$numeros[array_rand($numeros,1)],
                'ped_costo'=>$prod->precioreg,
                'ped_usrid'=>$folio->fol_usrid,
            ],
        ];
        foreach($compra as $i){  
            FoliosProdsModel::create($i);
            FoliosProdsModel::create($i);
            FoliosProdsModel::create($i);
            FoliosProdsModel::create($i);
            FoliosProdsModel::create($i);
        }
    }
}
