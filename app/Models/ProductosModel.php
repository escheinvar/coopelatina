<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductosModel extends Model
{
    protected $conection='mysql';
    protected $table = 'productos';
    protected $primaryKey ='id';

    protected $editable = [
        'activo',
        'gpo',
        'nombre',
        'variantes',
        'presentacion',
        'entrega',
        'venta',
        'existencias',
        'costo',
        'precioact',
        'precioreg',
        'preciopub',
        'cantmin',
        'proveedor',
        'categoria',
        'responsable',
        'descripcion',
        'img',
        'orden',
    ];
    
    protected $fillable = [
        'activo',
        'gpo',
        'nombre',
        'variantes',
        'presentacion',
        'entrega',
        'venta',
        'existencias',
        'costo',
        'precioact',
        'precioreg',
        'preciopub',
        'cantmin',
        'proveedor',
        'categoria',
        'responsable',
        'descripcion',
        'img',
        'orden',
    ];
}
