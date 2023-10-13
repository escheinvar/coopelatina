<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendario extends Model
{
    use HasFactory;
    #protected $connection='pgsql';
    protected $table = 'calendarios';
    #protected $primaryKey = 'work_id';
    #public $incrementing = true;
    #protected $keyType = 'string';
 
    protected $fillable=[
        'act',
        'anio',
        'mes',
        'event',
        'titulo',
        'start',
        'end',
        'responsable',
        'opciones',
        
    ];
}
