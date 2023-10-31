<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use App\Models\ProductoresModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        #$faker = Factory::create('es_MX');
        $regs=[
            [
            'prod_act'=>'1',
            'prod_nombrecorto'=>fake()->name(1),
            'prod_nombrelargo'=>fake()->name(),
            'prod_contacto'=>fake()->name(),
            'prod_tel'=>'5501234567',
            'prod_correo'=>fake()->email(),
            'prod_descripcion'=>fake()->realText(200), 
            'prod_direccion'=>fake()->text(),
            'prod_http'=>'http://www.coopelatina.org',
            'prod_facebook'=>'@coopelatina',
            'prod_instagram'=>'@coopelatina',
            'prod_youtube'=>'@coopelatina',
            'prod_tipo'=>'Cooperativa',
            'prod_logo'=>'',
            'prod_orden'=>'999',
            ],
        ];

        foreach($regs as $i){
            ProductoresModel::create($i);
        }
        
        
    }
}



