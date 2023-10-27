<?php

namespace Database\Seeders;

use App\Models\EstadosModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events =[
            [
                'edo_name'=>'EnPagos',
                'edo_edo'=>'1',
                'edo_explica'=>'Indica si aún se están procesando pagos;true=si y false=no; if true, tons no listas de abasto',
            ],
            [
                'edo_name'=>'Ocasion',
                'edo_edo'=>'0',
                'edo_explica'=>'Indica si se ofrecen productos de ocasión',
            ],
        ];

        foreach ($events as $event){
            EstadosModel::create($event);
        }
    }
}
