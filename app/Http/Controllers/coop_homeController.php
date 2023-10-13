<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use Illuminate\Http\Request;

class coop_homeController extends Controller
{
    public function index(string $usr){
        
        return view('home',['usr'=>$usr]);
    }
}
