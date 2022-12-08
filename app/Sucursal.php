<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sucursal extends Model
{
    use SoftDeletes;
    
    public function tienda()
    {
        return $this->belongsTo('App\Tienda');
    }

    /*public function entrega()
    {
        return $this->belongsTo('App\Entrega');
    }*/

    public function pedidos()
    {
        return $this->hasMany('App\Pedido');
    }
}
