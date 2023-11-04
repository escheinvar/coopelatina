<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hoy =date('Y-m-d h:i:s', time() );
        $EnUnAnio= date('Y-m-d H:i:s', strtotime('+1 year', time() ));

        
        $regs=[
            [
            'nombre'=>fake()->name(1),
            'ap_pat'=>fake()->name(1),
            'ap_mat'=>fake()->name(1),
            'usr'=>fake()->name(1),
            'activo'=>'1',
            'estatus'=>'reg',
            'priv'=>'usr',
            'tel'=>'55123456789',
            'mail'=>fake()->email(),
            'direccion'=>'Dirección',
            'dateregistro'=>$hoy,
            'membrefin'=>$EnUnAnio,
            'password'=>Hash::make('bla'),
            ],
        ];

        foreach($regs as $i){
            User::create($i);
        }
        
    }
}
