<?php

namespace Database\Seeders;

use App\Models\EnvasesModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnvasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events =[
            [
                'fco_act'=>'1',
                'fco_nombre'=>'Miel Chico',
                'fco_describe'=>'200ml de vídrio con tapa metal',
                'fco_costo'=>'10',
            ],
            [
                'fco_act'=>'1',
                'fco_nombre'=>'Miel Grande',
                'fco_describe'=>'500ml de vídrio con tapa metal',
                'fco_costo'=>'20',
            ],
        ];

        foreach ($events as $event){
            EnvasesModel::create($event);
        }
    }
}
