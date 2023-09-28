<?php

namespace App\Http\Controllers\admon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    public function index(){
        return view('admon.usuarios');
    }

    public function store(){
        return view('admon.usuarios');
    }
}
