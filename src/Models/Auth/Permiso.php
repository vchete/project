<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table      = 'auth_permisos';
    protected $primaryKey = 'permiso_id';
    protected $guarded    = ['permiso_id'];
}
