<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvasesModel extends Model
{
    use HasFactory;
    #protected $connection='pgsql';
    protected $table = 'envases';
    protected $primaryKey = 'fco_id';
    public $incrementing = true;
    #protected $keyType = 'string';
 
    protected $fillable=[
        'fco_act',
        'fco_nombre',
        'fco_describe',
        'fco_costo',
    ];
}
