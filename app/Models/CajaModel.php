<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaModel extends Model
{
    use HasFactory;
    #protected $connection='pgsql';
    protected $table = 'caja';
    protected $primaryKey = 'caja_id';
    public $incrementing = true;
    #protected $keyType = 'string';


    protected $fillable = [
        'caja_id',
        'caja_act',
        'caja_caja',
        'caja_teso',
        'caja_banco',
        'caja_responsable',
        'caja_usrid',
        'caja_tipo',
        'caja_descripcion',
        'caja_observaciones',
    ];
    
}
