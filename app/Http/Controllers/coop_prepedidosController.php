<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class coop_prepedidosController extends Controller
{
    public function index(){
        return view('prepedidos');
    }

    public function store(Request $request){
        #dd("A",$request->all());
        $confirma=[];
        $anualidad="";
        foreach($request->all() as $key=>$value){
            if(preg_match("/Anualidad/",$key)){
                $anualidad = $value;
            }

            #if($value > 0 AND preg_match("/com/",$key)){
            if($value > 0 AND (preg_match("/com/",$key) OR preg_match("/oca/",$key) ) ){
                $confirma[$key] = $value;
            }

            #if($value > 0 AND preg_match("/oca/",$key)){
            #    $ocasion[$key]=$value;
            #}
        }
        #dd($request);
        return view('prepedidos01',[
            'confirma'=>$confirma,
            'anualidad'=>$anualidad,
            #'ocasion'=>$ocasion
        ]);
    }
}
