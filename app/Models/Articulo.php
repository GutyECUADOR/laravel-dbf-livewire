<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';

    protected $fillable = [
        'codart', 'nomart', 'grupo', 'alterno', 'iva', 'unidad', 
        'peso', 'ubica', 'precio_a', 'precio_b', 'precio_c', 
        'precio_d', 'ult_costo', 'costo_act', 'existe_act', 
        'exmin', 'exmax', 'fecha_cos', 'fecha_sal', 'fec_dig', 
        'codcon', 'codcos', 'codbar'
    ];

    protected $casts = [
        'fecha_cos' => 'date',
        'fecha_sal' => 'date',
        'fec_dig'   => 'date',
        'precio_a'  => 'decimal:4',
        'existe_act' => 'decimal:4',
    ];
}