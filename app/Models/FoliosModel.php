<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoliosModel extends Model
{
    use HasFactory;
    
    #protected $connection='pgsql';
    protected $table = 'folios';
    protected $primaryKey = 'fol_id';
    public $incrementing = true;
    #protected $keyType = 'string';

    protected $fillable = [
        'fol_act',  #0, 1
        'fol_edo',  # 4:pedido 3:pagado 2:EntrParcial 1:EntregaTotal 0:cancelado
        'fol_anio',
        'fol_mes',
        'fol_usrid',
        'fol_usr',
        'fol_pagoimg',
        'fol_pagodate',
        'fol_canceldate',        
    ];
}
