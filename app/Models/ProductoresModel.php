<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoresModel extends Model
{
    use HasFactory;
     #protected $connection='pgsql';
     protected $table = 'productores';
     protected $primaryKey = 'prod_id';
     public $incrementing = true;
     #protected $keyType = 'string';
 
     protected $fillable = [
        'prod_act',
        'prod_nombrecorto',
        'prod_nombrelargo',
        'prod_contacto',
        'prod_tel',
        'prod_correo',
        'prod_descripcion',
        'prod_direccion',
        'prod_http',
        'prod_facebook',
        'prod_instagram',
        'prod_youtube',
        'prod_tipo',
        'prod_logo',
        'prod_fotos',
        'prod_orden',  
     ];
}
