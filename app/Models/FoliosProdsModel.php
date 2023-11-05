<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoliosProdsModel extends Model
{
    use HasFactory;
    #protected $connection='pgsql';
    protected $table = 'folios_prods';
    protected $primaryKey = 'ped_id';
    public $incrementing = true;
    #protected $keyType = 'string';


    protected $fillable = [
        'ped_act',
        'ped_folio',
        'ped_producto',
        'ped_entregado',
        'ped_entrega',
        'ped_prodid',
        'ped_prod',
        'ped_prodvar',
        'ped_prodpresenta',
        'ped_costo',
        'ped_cant',
        'ped_usrid',
        'ped_cantentregada',
        'ped_entregadate',
        'ped_transfiere',
    ];
}
