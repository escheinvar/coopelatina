<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TockenRecuperaPass extends Model
{

    use HasFactory;

    #protected $conection='mysql';
    protected $table = 'tocken_recupera_pass';
    protected $primaryKey ='id';

    protected $fillable = [
        'usr',
        'tocken',
        'creacion',
        'caduca',
        'act',
    ];
}
