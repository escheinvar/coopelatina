<?php

namespace App\Http\Controllers\admon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbastoController extends Controller
{
    public function index(){
        
        return view('admon.Abasto');
    }
}
