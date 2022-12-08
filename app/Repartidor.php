<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repartidor extends Model
{
    public function solicitudesUbicacion()
    {
        return $this->hasMany('App\SolicitudUbicacion');
    }

    public function usuario()
    {
    	return $this->belongsTo('App\User');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function entregas()
    {
        return $this->hasMany('App\Entrega');
    }
}
