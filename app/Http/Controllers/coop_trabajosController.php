<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class coop_trabajosController extends Controller
{
    public function index(){
        $GranVariable='conMenuHome';
        return view('trabajos',['GranVariable'=>$GranVariable]);
    } 
}
