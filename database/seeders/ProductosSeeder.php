<?php

namespace Database\Seeders;

use App\Models\ProductoresModel;
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
        $productos=['Amaranto Harina','Amaranto Barras','Alegrias','Amaranto Obleas',
            'Amaranto mega barras','Miel','Chocolate','Café','Leche','Chiles','Paletas','Miel de maguey','Miel colmenenta','Miel unifloral',
            'Chocobarras','Obleas','Churritos amaranto','Amaranto tostado', 'Café', 'Leche fresca', 'Queso Oaxaca', 'Queso Ranchero',
            'Queso Panela', 'Huevo de 12','Huevo de 24', 'Yogurth', 'Baguette chica','Baguette grande', 'Bolillo','Cuernitos','Chapata','Rol de canela','Rol de Glass',
            'Galleta chocolate','Galleta amaranto','Panqué chocoloate','Panqué amaranto','Paleta Propoleo','Jitomante','Limón','Papa','Cebolla','Pera','Manzana','Queso Provolone',
            'Queso Chihuahua','Queso Gouda','Requesón','Humus', 'Pan cereal','Pan ajo','Pan caja','Cartera Guayaba', 'Cartera Chocolate', 'Chocolatin', 'Pez bandera','Filete Bandera',
            'Picado Bandera','Jurel','Barritas de pescado','Camarón cristal','Camarón pacotilla','Salmón en lonja','Salmón','Cerveza','Frijol','Sal','Arroz','Aguacate','Tortilla azul',
            'Tortilla amarilla','Pinol','Cloro','Lavamanos','Lavatrastes','Lavaropa','Suavisante','lata 1','camisa','camiseta','pantalon','jabón','shampoo','limipa cara','crema','ajo',
            'Croquetas','Alpiste','Correas'];
        $sabores=['','SaborA,SaborB', '', 'SaborA,SaborB,SaborC', '', 'SaborA,SaborB,SaborC,SaborD'];
        
        $events =[
            [
                'activo'=>'1',
                'gpo'=>'Aleatorio',
                'nombre'=>$productos[array_rand($productos,1)],
                'variantes'=>$sabores[array_rand($sabores,1)],
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
                'proveedor'=>ProductoresModel::inRandomOrder()->first()->prod_nombrecorto,
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
