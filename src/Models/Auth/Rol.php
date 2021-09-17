<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table      = 'auth_roles';
    protected $primaryKey = 'rol_id';
    protected $dates      = ['created_at', 'updated_at', 'deleted_at'];
    protected $guarded    = ['rol_id'];

    public function modulosPermisos () {
        return $this->hasMany(RolModuloPermiso::class, 'rol_id', 'rol_id');
    }
}
