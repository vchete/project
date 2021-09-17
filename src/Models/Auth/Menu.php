<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table      = 'auth_menu';
    protected $primaryKey = 'menu_id';
    protected $guarded    = ['menu_id'];

    public function moduloPermiso () {
        return $this->hasOne(ModuloPermiso::class, 'modulo_permiso_id', 'modulo_permiso_id');
    }
}
