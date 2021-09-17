<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class ModuloPermiso extends Model
{
    protected $table      = 'auth_modulo_permisos';
    protected $primaryKey = 'modulo_permiso_id';
    protected $guarded    = ['modulo_permiso_id'];

    public function modulo() {
        return $this->hasOne(Modulo::class, 'modulo_id', 'modulo_id');
    }

    public function permiso() {
        return $this->hasOne(Permiso::class, 'permiso_id', 'permiso_id');
    }
}
