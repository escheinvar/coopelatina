<?php

namespace App\Http\Controllers;

use App\Models\ProductoresModel;
use App\Models\ProductosModel;
use Illuminate\Http\Request;

class coop_productoresController extends Controller
{
    public function index(){
        return view('productores');
    }
}
