<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Calendario;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        if( !auth()->user() ){
            return view('acceso.login')->with('mensaje','');
        }else{
            return redirect()->route('home',['usr'=>auth()->user()->usr]);
        }
    }

    public function store(Request $request){
        $request['usr']=strtolower($request->usr);
        ### Valida el formulario de inicio        
        $this->validate($request,[
            'usr'=>'required',
            'password'=> 'required',    
        ]);
        
        ### Autentica
        
        if(!auth()->attempt($request->only('usr','password'))){
            session([]);
            return view('acceso.login')->with('mensaje','Usuario o contraseña incorrectas');
        }       

        
        
        
        ############################### Define variables de sistema 
        session([
            'arraySemana'=>['domingo','lunes','martes','miércoles','jueves','viernes','sábado'],
            'arrayMes'=>['Mes','ene','feb','mzo','abr','may','jun','jul','ago','sep','oct','nov','dic'],
            'arrayMeses'=>['Mes','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'],
        ]);
        

        return redirect()->route('home',['usr'=>auth()->user()->usr]);
    }
}
