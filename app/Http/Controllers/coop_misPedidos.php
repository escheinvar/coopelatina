<?php

namespace App\Http\Controllers;

use App\Models\FoliosModel;
use Illuminate\Http\Request;
use App\Models\FoliosProdsModel;
use Illuminate\Support\Facades\DB;

class coop_misPedidos extends Controller
{
    public function index(string $usr){
        return view('mispedidos');
    }
}
