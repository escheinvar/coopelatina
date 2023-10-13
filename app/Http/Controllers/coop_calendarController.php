<?php

namespace App\Http\Controllers;

use App\Models\Calendario;
use DateTime;
use Illuminate\Database\DBAL\TimestampType;
use Illuminate\Http\Request;

class coop_calendarController extends Controller
{
    
    public function index(){
        return view('calendario');
    }

    public function store(){
       # return(view('calendario'));
    }
    
}
