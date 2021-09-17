<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    protected $table      = 'auth_usuario_roles';
    protected $primaryKey = 'usuario_rol_id';
    protected $guarded    = ['usuario_rol_id'];
}
