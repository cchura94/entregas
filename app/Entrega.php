<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    public function pedido()
    {
        return $this->belongsTo('App\Pedido');
        //return $this->hasOne('App\Pedido');
    }

    /*public function sucursal()
    {
        return $this->hasMany('App\Sucursal');
    }*/

    public function repartidor()
    {
        return $this->belongsTo('App\Repartidor');
    }
}
