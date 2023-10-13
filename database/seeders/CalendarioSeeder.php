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
        #$anio='2023'; $mes='11';
     
  
        $events =[
            [
                'event' => 'com1',
                'mes'=>$mes,
                'anio'=>$anio,
                'titulo'=> '1a (Enrique)',
                'start' => $anio."-".$mes."-15 00:01",
                'end' => $anio."-".$mes."-15 23:59",
                'act'=>'1',
                'responsable'=>'Enrique',
                'opciones'=>'Camilo,Luciana',
            ],
            [
                'event' => 'com2',
                'mes'=>$mes,
                'anio'=>$anio,
                'titulo'=> '2a (Niza)',
                'start' => $anio."-".$mes."-25 00:01",
                'end' => $anio."-".$mes."-25 23:59",
                'act'=>'1',
                'responsable'=>'Niza',
                'opciones'=>'Camilo,Luciana',
            ],
            [
                'event' => 'ped',
                'mes'=>$mes,
                'anio'=>$anio,
                'titulo'=> 'Pedidos octubre',
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
                'titulo'=> 'Visitar a Proveedor',
                'start' => $anio."-".$mes."-01 00:01",
                'end' => $anio."-".$mes."-01- 23:59",
                'act'=>'1',
                'responsable'=>'Luciana',
                'opciones'=>'Niza,Luciana',
            ],
        ];

        foreach ($events as $event){
            Calendario::create($event);
        }
        
    }
}
