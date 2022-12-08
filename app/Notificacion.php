<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
