<?php

namespace App\Http\Controllers\admon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OcasionController extends Controller
{
    public function index(){
        return view('ocasion');
    }

    #public function store(Request $request){
    #    return view('prepedidos01',['confirma'=>$confirma,'anualidad'=>$anualidad]);
    #}
}
