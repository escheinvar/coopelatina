<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class coop_prepedidosController extends Controller
{
    public function index(){
        return view('prepedidos');
    }

    public function store(Request $request){
        $confirma=[];
        $anualidad="";
        foreach($request->all() as $key=>$value){
            if(preg_match("/Anualidad/",$key)){
                $anualidad = $value;
            }

            if($value > 0 AND preg_match("/com/",$key)){
                $confirma[$key] = $value;
            }
        }
        #dd($request);
        return view('prepedidos01',['confirma'=>$confirma,'anualidad'=>$anualidad]);
    }
}
