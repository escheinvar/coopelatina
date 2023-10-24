<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrabajosModel extends Model
{
    use HasFactory;
    #protected $connection='pgsql';
    protected $table = 'trabajos';
    protected $primaryKey = 'work_id';
    public $incrementing = true;
    #protected $keyType = 'string';


    protected $fillable = [
        'work_usrid',
        'work_act',
        'work_usr',
        'work_mes',
        'work_anio',
        'work_anualidadvence',
        'word_descripcion',
        'work_fechatrabajo',
        'work_responsableid',
    ];
}
