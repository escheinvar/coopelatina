<?php

namespace Database\Seeders;

use App\Models\ProductosModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events =[
            [
                'activo'=>'1',
                'gpo'=>'Café',
                'nombre'=>'Café',
                'variantes'=>'Molido, En grano',
                'presentacion'=>'Paquetes de 1kg',
                'entrega'=>'com12',
                'venta'=>'si',
                'existencias'=>'1',
                'costo'=>'80.0',
                'precioact'=>'80.0',
                'precioreg'=>'85.0',
                'preciopub'=>'95.0',
                'mintipo'=>'1',
                'min'=>'10',
                'proveedor'=>'Cooperativa de café',
                'categoria' =>'cafeteria',
                'responsable'=>'admin',
                'descripcion'=>'Rico café de altura',
                'img'=>'null',
                'orden'=>'1'
            ],
        ];

        foreach ($events as $event){
            ProductosModel::create($event);
        }
    }
}
