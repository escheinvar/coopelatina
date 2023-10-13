<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hoy =date('Y-m-d h:i:s', time() );
        $EnUnAnio= date('Y-m-d H:i:s', strtotime('+1 year', time() ));
        $events =[
            [
                'nombre' => 'Administrador',
                'ap_pat'=>'Admin',
                'ap_mat'=> 'Admin',
                'usr' => 'admin',
                'estatus' => 'reg',
                'priv'=>'root',
                'tel'=>'5512345678',
                'mail'=>'admin@prueba.com',
                'direccion'=>'Local de la Coope',
                'dateregistro'=> $hoy,
                'membrefin'=> $EnUnAnio,
                'password'=>Hash::make('admin') ,
            ],
        ];

        foreach ($events as $event){
            User::create($event);
        }
    }
}
