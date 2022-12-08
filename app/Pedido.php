<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
	use SoftDeletes;
    public function entrega()
    {
    	return $this->hasOne('App\Entrega');
        //return $this->belongsTo('App\Entrega');
    }	

    public function sucursal()
    {
    	return $this->belongsTo('App\Sucursal');
    }

}
