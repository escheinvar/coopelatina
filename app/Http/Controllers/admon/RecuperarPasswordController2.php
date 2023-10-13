<?php

namespace App\Http\Controllers\admon;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TockenRecuperaPassModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RecuperarPasswordController2 extends Controller
{
    public function index(string $tocken, string $usr){
        ### Busca tocken y correo y que esté activo y vigente
        $ganones=TockenRecuperaPassModel::where('act','1')
            ->where('usr',$usr)
            ->where('tocken',$tocken)
            ->first();

        ### Establece acción
        if($ganones==null){
            $aviso="nohay";

        }elseif ($ganones->caduca < now() ){
            $aviso="caduco";

        }else{
            $aviso="procede";
        }

        return view('acceso.RecuperarPassword3',['aviso'=>$aviso,'usr'=>$usr]);
    }

    public function store(Request $request, string $tocken, string $usr){

        $request->validate([
            'pass' => 'required|confirmed|min:1',
        ]);
        $ganones=TockenRecuperaPassModel::where('act','1')
            ->where('usr',$usr)
            ->where('tocken',$tocken)
            ->where('caduca','>',now())
            ->count();
        if($ganones == 1){
            $aviso="1";
            
            ### Cambia el passwd en la base
            User::where('usr',$request->usr)->update([
                'password' =>  Hash::make($request->pass),
            ]);

            ### Inactiva todos los tockens del usuario
            TockenRecuperaPassModel::where('usr',$usr)->where('act','1')->update(['act'=>'0']);

            ### Envía correo electrónico de confirmación
            #Mail::to($usuario->mail)->send(new RecuperarContrasena($datos) );
        }else{
            $aviso="0";
            
        }

        return view('acceso.RecuperarPassword4',['aviso'=>$aviso]);
    }
}