<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\productosModel;

class coop_prepedidosController extends Controller
{
    public function index(){
        #$todo = productosModel::where('activo','1')
        #    ->where('entrega','not like','no')
        #    ->orderBy('gpo')
        #    ->get();
        #return view('prepedidos',compact('todo') );
        return view('prepedidos');
    }



    public function store(Request $request){
        
        #$pedido = array();
        ##dd("ja",$request->all());
        #foreach($request->all() as $key=>$value){
        #    if( $value > 0 & preg_match('/com/',$key) ){
        #        $id = preg_replace('/com._/','',$key); 
        #        $entrega = preg_replace('/_.*/',"",$key); 
        #        $sabor=$request->{'sab_'.$id};
        #        $todo = productosModel::where('id',$id)->first();
        #        $pedido[$key]=[$id,$entrega,$value,$todo->nombre,$sabor];
        #    }
        #}
        #session(['pedido'=>$pedido]);
        #$todo = productosModel::where('activo','1')
        #    ->where('entrega','not like','no')
        #    ->orderBy('gpo')
        #    ->get();
        #return view('prepedidosConfir',compact('todo'));
    }
}
