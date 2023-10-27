<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadosModel extends Model
{
    use HasFactory;
    #protected $connection='pgsql';
    protected $table = 'estados';
    protected $primaryKey = 'edo_id';
    public $incrementing = true;
    #protected $keyType = 'string';

    protected $fillable = [
        'edo_name',  # Nombre del estado
        'edo_edo',  # Estado actual
        'edo_explica', #Texto explicativo del estado
    ];
}
