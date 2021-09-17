<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class RolModuloPermiso extends Model
{
    protected $table      = 'auth_rol_modulo_permisos';
    protected $primaryKey = 'rol_modulo_permiso_id';
    protected $guarded    = ['rol_modulo_permiso_id'];

    public function moduloPermiso() {
        return $this->hasOne(ModuloPermiso::class, 'modulo_permiso_id', 'modulo_permiso_id');
    }
}
