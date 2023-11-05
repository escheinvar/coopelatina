<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbastosModel extends Model
{
    use HasFactory;

     #protected $connection='pgsql';
     protected $table = 'abastos';
     protected $primaryKey = 'aba_id';
     public $incrementing = true;
     #protected $keyType = 'string';
 
 
     protected $fillable = [
        'aba_anio',
        'aba_mes',
        'aba_com',
        'aba_prodid',
        'aba_prodsabor',
        'aba_producto',
        'aba_listas',
        'aba_listas_cantpeds',
        'aba_listas_canttien',
        'aba_abasto',
        'aba_abasto_cant',
        'aba_abasto_date',
        'aba_faltante',
     ];
}
