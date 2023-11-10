<?php

namespace App\Http\Controllers\admon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index(){
        return view('admon.Caja');
    }
}
