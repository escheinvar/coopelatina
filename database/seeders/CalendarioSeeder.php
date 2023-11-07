<?php

namespace Database\Seeders;

use App\Models\Calendario;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CalendarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        $anio=date("Y", strtotime("now"));
        $mes=date("m", strtotime("+1 month"));
        $anio2=date("Y", strtotime("+10 year"));
  
        $events =[
            [
                'event' => 'com1',
                'mes'=>$mes,
                'anio'=>$anio2,
                'titulo'=> '1a (Encargado del futuro)',
                'start' => $anio2."-".$mes."-15 00:01",
                'end' => $anio2."-".$mes."-15 23:59",
                'act'=>'1',
                'responsable'=>'admin',
                'opciones'=>'Camilo,Luciana',
            ],
            [
                'event' => 'com2',
                'mes'=>$mes,
                'anio'=>$anio2,
                'titulo'=> '2a (Encargado del futuro)',
                'start' => $anio2."-".$mes."-25 00:01",
                'end' => $anio2."-".$mes."-25 23:59",
                'act'=>'1',
                'responsable'=>'admin',
                'opciones'=>'Camilo,Luciana',
            ],
            [
                'event' => 'ped',
                'mes'=>$mes,
                'anio'=>$anio2,
                'titulo'=> 'Pedidos del futuro',
                'start' => $anio2."-".$mes."-05 00:01",
                'end' => $anio2."-".$mes."-10- 23:59",
                'act'=>'1',
                'responsable'=>'admin',
                'opciones'=>'Niza,Luciana',
            ],
            [
                'event' => 'evento',
                'mes'=>$mes,
                'anio'=>$anio2,
                'titulo'=> 'Visitar a Proveedor',
                'start' => $anio2."-".$mes."-01 00:01",
                'end' => $anio2."-".$mes."-01- 23:59",
                'act'=>'1',
                'responsable'=>'Luciana',
                'opciones'=>'Niza,Luciana',
            ],                                                                                                          
            


            [
                'event' => 'com1',
                'mes'=>$mes,
                'anio'=>$anio,
                'titulo'=> '1a (Encargado)',
                'start' => $anio."-".$mes."-15 00:01",
                'end' => $anio."-".$mes."-15 23:59",
                'act'=>'1',
                'responsable'=>'admin',
                'opciones'=>'Camilo,Luciana',
            ],
            [
                'event' => 'com2',
                'mes'=>$mes,
                'anio'=>$anio,
                'titulo'=> '2a (Encargado)',
                'start' => $anio."-".$mes."-25 00:01",
                'end' => $anio."-".$mes."-25 23:59",
                'act'=>'1',
                'responsable'=>'admin',
                'opciones'=>'Camilo,Luciana',
            ],
            [
                'event' => 'ped',
                'mes'=>$mes,
                'anio'=>$anio,
                'titulo'=> 'Pedidos del mes',
                'start' => $anio."-".$mes."-05 00:01",
                'end' => $anio."-".$mes."-10- 23:59",
                'act'=>'1',
                'responsable'=>'Camilo',
                'opciones'=>'Niza,Luciana',
            ],
            [
                'event' => 'evento',
                'mes'=>$mes,
                'anio'=>$anio,
                'titulo'=> 'Evento 1',
                'start' => $anio."-".$mes."-01 00:01",
                'end' => $anio."-".$mes."-01- 23:59",
                'act'=>'1',
                'responsable'=>'admin',
                'opciones'=>'Niza,Luciana',
            ], 
        ];

        foreach ($events as $event){
            Calendario::create($event);
        }
        
    }
}
