<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function repartidor()
    {
        return $this->hasOne('App\Repartidor');
    }

    public function notificacion()
    {
        return $this->hasMany(Notificacion::class);
    }

    public function autorizarRol($roles)
    {
        if($this->tieneMasRoles($roles)){
            return true;
        }
        abort(401, "Esta acción no esta autorizada");      
    }

    public function tieneMasRoles($roles)
    {
        if(is_array($roles)){
            foreach ($roles as $r) {
                if($this->tieneRol($r)){
                    return true;
                }
            }
        }else{
            if($this->tieneRol($roles)){
                return true;
            }
        }
        return false;
    }

    //Si tiene un Role
    public function tieneRol($role)
    {
        if($this->roles()->where('nombre', $role)->first()){
            return true;
        }
        return false;
    }
}
