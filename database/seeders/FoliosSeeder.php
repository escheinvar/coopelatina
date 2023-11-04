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
    {
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
        
        $prod=ProductosModel::inRandomOrder()->first();
        $comandas=['com1','com2'];
        $sabores=['SaborA','SaborB'];
        $numeros=['1','2','3','4','5','6'];
        ##################
        $prod1=ProductosModel::inRandomOrder()->first();
        $comandas1=['com1','com2'];
        $sabores1=['SaborA','SaborB'];
        $numeros1=['1','2','3','4','5','6'];
        #################
        $prod2=ProductosModel::inRandomOrder()->first();
        $comandas2=['com1','com2'];
        $sabores2=['SaborA','SaborB'];
        $numeros2=['1','2','3','4','5','6'];
        ###################
        $prod3=ProductosModel::inRandomOrder()->first();
        $comandas3=['com1','com2'];
        $sabores3=['SaborA','SaborB'];
        $numeros3=['1','2','3','4','5','6'];
        #####################
        $prod4=ProductosModel::inRandomOrder()->first();
        $comandas4=['com1','com2'];
        $sabores4=['SaborA','SaborB'];
        $numeros4=['1','2','3','4','5','6'];
        $compra = [
            [
                'ped_folio'=>$folio->fol_id,
                'ped_entrega'=>$comandas[array_rand($comandas,1)],
                'ped_prodid'=>$prod->id,
                'ped_prod'=>$prod->nombre,
                'ped_prodvar'=>$sabores[array_rand($sabores,1)],
                'ped_prodpresenta'=>$prod->presentacion,
                'ped_cant'=>$numeros[array_rand($numeros,1)],
                'ped_costo'=>$prod->precioreg,
                'ped_usrid'=>$folio->fol_usrid,
            ],
            [
                'ped_folio'=>$folio->fol_id,
                'ped_entrega'=>$comandas1[array_rand($comandas1,1)],
                'ped_prodid'=>$prod1->id,
                'ped_prod'=>$prod1->nombre,
                'ped_prodvar'=>$sabores1[array_rand($sabores1,1)],
                'ped_prodpresenta'=>$prod1->presentacion,
                'ped_cant'=>$numeros1[array_rand($numeros1,1)],
                'ped_costo'=>$prod1->precioreg,
                'ped_usrid'=>$folio->fol_usrid,
            ],
            [
                'ped_folio'=>$folio->fol_id,
                'ped_entrega'=>$comandas2[array_rand($comandas2,1)],
                'ped_prodid'=>$prod2->id,
                'ped_prod'=>$prod2->nombre,
                'ped_prodvar'=>$sabores2[array_rand($sabores2,1)],
                'ped_prodpresenta'=>$prod2->presentacion,
                'ped_cant'=>$numeros2[array_rand($numeros2,1)],
                'ped_costo'=>$prod2->precioreg,
                'ped_usrid'=>$folio->fol_usrid,
            ],
            [
                'ped_folio'=>$folio->fol_id,
                'ped_entrega'=>$comandas3[array_rand($comandas3,1)],
                'ped_prodid'=>$prod3->id,
                'ped_prod'=>$prod3->nombre,
                'ped_prodvar'=>$sabores3[array_rand($sabores3,1)],
                'ped_prodpresenta'=>$prod3->presentacion,
                'ped_cant'=>$numeros3[array_rand($numeros3,1)],
                'ped_costo'=>$prod3->precioreg,
                'ped_usrid'=>$folio->fol_usrid,
            ],
            [
                'ped_folio'=>$folio->fol_id,
                'ped_entrega'=>$comandas4[array_rand($comandas4,1)],
                'ped_prodid'=>$prod4->id,
                'ped_prod'=>$prod4->nombre,
                'ped_prodvar'=>$sabores4[array_rand($sabores4,1)],
                'ped_prodpresenta'=>$prod4->presentacion,
                'ped_cant'=>$numeros4[array_rand($numeros4,1)],
                'ped_costo'=>$prod4->precioreg,
                'ped_usrid'=>$folio->fol_usrid,
            ],
        ];
        foreach($compra as $i){  
            FoliosProdsModel::create($i);
        }
    }
}
