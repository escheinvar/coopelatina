<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('acceso.login')->with('mensaje','');
    }

    public function store(Request $request){
        #dd("fin", $request->session(), session());
        
        $this->validate($request,[
            'usr'=>'required',
            'password'=> 'required',    
        ]);
        
        $request['usr']=strtolower($request->usr);

        if(!auth()->attempt($request->only('usr','password'))){
            session([]);
            return view('acceso.login')->with('mensaje','Usuario o contraseña incorrectas');
        }       

        ############################### Define variables de sistema del usuario
        $usuario= Auth::user();
        #dd("fin",$usuario);
        session([
            'usr_name'=>$usuario->nombre,
            'usr_usr' =>$usuario->usr,
            'usr_estatus'=>$usuario->estatus,  #pru, reg, act
            'usr_privs'=>$usuario->priv, #root,admon,teso,usr
            'usr_membre'=>$usuario->DateMembre,
            'prep'=>[]
        ]);
        return redirect()->route('prepedido');
    }
}
