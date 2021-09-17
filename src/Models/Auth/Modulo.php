<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table      = 'auth_modulos';
    protected $primaryKey = 'modulo_id';
    protected $guarded    = ['modulo_id'];

    public function moduloPermisos () {
        return $this->hasMany(ModuloPermiso::class, 'modulo_id', 'modulo_id');
    }
}
