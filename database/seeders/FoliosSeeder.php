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
        $prod1=ProductosModel::inRandomOrder()->where('activo','1')->whereIn('entrega',['com1','com2','comid','com12'])->first();
        if($prod1->entrega=='com12' OR $prod1->entrega=='comid'){$entrega1=['com1','com2'][array_rand([0,1],1)];}else{$entrega1=$prod1->entrega;}
        $numeros1=['1','2','3','4','5','6'];
        $sabores1=explode(",",$prod1->variantes);
        $sabor1=$sabores1[array_rand($sabores1,1)];
        ####################################################### CREA Productos para el folio
        $prod2=ProductosModel::inRandomOrder()->where('activo','1')->whereIn('entrega',['com1','com2','comid','com12'])->first();
        if($prod2->entrega=='com12' OR $prod2->entrega=='comid'){$entrega2=['com1','com2'][array_rand([0,1],1)];}else{$entrega2=$prod2->entrega;}
        $numeros2=['1','2','3','4','5','6'];
        $sabores2=explode(",",$prod2->variantes);
        $sabor2=$sabores2[array_rand($sabores2,1)];
        ####################################################### CREA Productos para el folio
        $prod3=ProductosModel::inRandomOrder()->where('activo','1')->whereIn('entrega',['com1','com2','comid','com12'])->first();
        if($prod3->entrega=='com12' OR $prod3->entrega=='comid'){$entrega3=['com1','com2'][array_rand([0,1],1)];}else{$entrega3=$prod3->entrega;}
        $numeros3=['1','2','3','4','5','6'];
        $sabores3=explode(",",$prod3->variantes);
        $sabor3=$sabores3[array_rand($sabores3,1)];

        $compra = [
            [
                'ped_folio'=>$folio->fol_id,
                'ped_producto'=>$entrega1.":".$prod1->id."@".$sabor1,
                'ped_entrega'=>$entrega1,
                'ped_prodid'=>$prod1->id,
                'ped_prod'=>$prod1->nombre,
                'ped_prodvar'=>$sabor1,
                'ped_prodpresenta'=>$prod1->presentacion,
                'ped_cant'=>$numeros1[array_rand($numeros1,1)],
                'ped_costo'=>$prod1->precioreg,
                'ped_usrid'=>$folio->fol_usrid,
            ],
            [
                'ped_folio'=>$folio->fol_id,
                'ped_producto'=>$entrega2.":".$prod2->id."@".$sabor2,
                'ped_entrega'=>$entrega2,
                'ped_prodid'=>$prod2->id,
                'ped_prod'=>$prod2->nombre,
                'ped_prodvar'=>$sabor2,
                'ped_prodpresenta'=>$prod2->presentacion,
                'ped_cant'=>$numeros2[array_rand($numeros2,1)],
                'ped_costo'=>$prod2->precioreg,
                'ped_usrid'=>$folio->fol_usrid,
            ],
            [
                'ped_folio'=>$folio->fol_id,
                'ped_producto'=>$entrega3.":".$prod3->id."@".$sabor3,
                'ped_entrega'=>$entrega3,
                'ped_prodid'=>$prod3->id,
                'ped_prod'=>$prod3->nombre,
                'ped_prodvar'=>$sabor3,
                'ped_prodpresenta'=>$prod3->presentacion,
                'ped_cant'=>$numeros3[array_rand($numeros3,1)],
                'ped_costo'=>$prod3->precioreg,
                'ped_usrid'=>$folio->fol_usrid,
            ],
        ];
        foreach($compra as $i){  
            FoliosProdsModel::create($i);
        }
    }
}
