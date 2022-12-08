<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudUbicacion extends Model
{
    public function repartidor()
    {
        return $this->belongsTo('App\Repartidor');
    }
}
