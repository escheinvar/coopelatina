<?php

namespace App\Http\Controllers\admon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagoPedidosController extends Controller
{
    public function index(){
        return view('admon.PagoPedidos');
    }
    public function store(){
        return view('admon.PagoPedidos');
    }
}

