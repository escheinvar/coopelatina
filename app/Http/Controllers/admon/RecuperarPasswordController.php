<?php

namespace App\Http\Controllers\admon;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\RecuperarContrasena;
use App\Http\Controllers\Controller;
use App\Models\TockenRecuperaPassModel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Mail\Mailable;

class RecuperarPasswordController extends Controller
{
    public function index(){
        return view('acceso.RecuperarPassword');
    }

    public function store(request $request){    
        $request['usr'] = strtolower($request->usr);
        
        $request->validate([
            'usr' => 'required|exists:users,usr',
        ]);
        
        $usuario = User::where('usr',$request->usr)->first();

        ### Si no hay usuario en la base....
        if($usuario == null){
            $datos=['texto'=>"No existe el usuario ".$request->usr,'tocken'=>null,];

        ### Sihay usuario en la base pero no tiene correo ....
        }else if( strlen($usuario->mail) < 3){
            $datos=['texto'=>"No hay correo registrado para el usuario ".$request->usr, 'tocken'=>null,];

        ### Si hay usuario y sí hay correo (todo ok)...
        }else{
            #Testa el correo 
            $testado=substr_replace($usuario->mail,'***@***',3,-7);

            #Genera datos de tocken
            function GeneraTocken($n) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randomString = '';            
                for ($i = 0; $i < $n; $i++) {
                    $index = rand(0, strlen($characters) - 1);
                    $randomString .= $characters[$index];
                }
                return $randomString;
            }
            $tocken=GeneraTocken(100);
            $date=date("Y-m-d G:i:s");
            $date2=date("Y-m-d G:i:s", strtotime('+20 minutes'));
            
            #Si ya existe un registro previo, y vigente lo inactiva
            TockenRecuperaPassModel::where('usr',$usuario->usr)
                #->where('caduca','>',now())
                ->Where('act','1')
                ->update(['act'=>'0']);

            #Crea el nuevo registro
            TockenRecuperaPassModel::create([
                'usr' => $usuario->usr,
                'tocken' => $tocken, 
                'creacion' => $date,
                'caduca' => $date2,
                'act'=>'1',
            ]);     
            
            $dir=$request->root()."/recupera_pass/".$tocken."/".$usuario->usr;
            #Envía correo
            $datos=['texto'=>$testado,'tocken'=>$tocken,'dir'=>$dir,'vence'=>$date2];
            Mail::to($usuario->mail)->send(new RecuperarContrasena($datos) );
        }        

        return view('acceso.RecuperarPassword2',['datos'=>$datos]);
    }
}

