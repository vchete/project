<?php

namespace App\Http\Controllers\Backend\Admin;

use DB;
use Auth;
use Crypt;
use Validator;

use App\Models\Auth\Rol;
use App\Models\Auth\Modulo;
use Illuminate\Http\Request;
use App\Models\Auth\ModuloPermiso;
use App\Models\Auth\RolModuloPermiso;
use Hcode\Project\Http\Controllers\AppController;
use Hcode\Project\Http\Controllers\AppCrudController;

class RolesController extends AppCrudController
{
    public function __construct(Request $request)
    {
        // $this->setVue();
        $this->setTitle('Roles');
        $this->setModel(new Rol);
        $this->setField(['field'=> 'nombre', 'name' => 'Rol', 'validate'=>'required']);
        $this->setField(['field'=> 'descripcion', 'name' => 'Descripción']);
        $this->setWhere('deleted_at', '=', null);

        $this->middleware(function ($request, $next) {
            if (Auth::id() != 1) {
                $this->setWhere('rol_id', '!=', 1);
            }

            return $next($request);
        });

        $this->setModuleAccess('admin.roles');
    }

    public function create(Request $request) {
        return $this->edit($request, Crypt::encrypt(0));
    }

    public function edit(Request $request, $rowId) {
        try {
            $rowId = Crypt::decrypt($rowId);
        }
        catch (Exception $e) {
            $request->session()->flash('alert-msj', 'Error al encontrar el registro.');
            $request->session()->flash('alert-type', 'danger');

            return redirect()->back();
        }

        $data   = Rol::with(['modulosPermisos' => function ($q) {
            $q->select('rol_id', 'mp.modulo_permiso_id')
                ->leftJoin('auth_modulo_permisos as mp', 'mp.modulo_permiso_id', '=', 'auth_rol_modulo_permisos.modulo_permiso_id')
                ->leftJoin('auth_permisos as p', 'p.permiso_id', '=', 'mp.permiso_id')
                ->where('p.mostrar', true);;
        }])->find($rowId);

        $modulos = Modulo::with([
                'moduloPermisos' => function ($q) {
                    $q->select('modulo_permiso_id', 'modulo_id', 'p.permiso_id', 'p.nombre_friendly')
                        ->leftJoin('auth_permisos as p', 'p.permiso_id', '=', 'auth_modulo_permisos.permiso_id')
                        ->where('p.mostrar', true);
                },
            ])->select('modulo_id', 'nombre_friendly')
            ->orderBy('nombre')
            ->get();

        return view('backend.admin.roles.edit', [
            'tituloPagina' => 'Rol | <span style = "font-size: 12px;">'. ($data ? 'Editar': 'Nuevo') .'</span>',
            'data'         => $data,
            'modulos'      => $modulos
        ]);
    }

    public function store(Request $request) {
        return $this->update($request, Crypt::encrypt(0));
    }

    public function update(Request $request, $rowId) {
        try {
            $rowId = Crypt::decrypt($rowId);
        }
        catch(Exception $e){
            AppController::alertError($request, $e);
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            'nombre' => 'required'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        if ($rowId == 0) {
            $rol = Rol::firstOrNew(['nombre' => $request->nombre]);
        } 
        else {
            $rol = Rol::find($rowId);
        }
        try {
            DB::transaction(function () use ($request, $rol) {
                $rol->nombre      = $request->nombre;
                $rol->descripcion = $request->descripcion;
                $rol->deleted_at  = null;
                $rol->save();

                $rolId = $rol->rol_id;
                RolModuloPermiso::where('rol_id', $rolId)->delete();
                if ($request->modulosPermisos) {
                    foreach ($request->modulosPermisos as $moduloId => $permisos) {
                        $moduloPermisos = ModuloPermiso::select('modulo_permiso_id','permiso_id')->where('modulo_id', $moduloId)->get();

                        foreach ($permisos as $id => $p) {
                            $rolModuloPermiso = RolModuloPermiso::firstOrNew(['rol_id' => $rolId, 'modulo_permiso_id' => $id]);
                            $rolModuloPermiso->save();
    
                            if ($moduloPermisos->where('modulo_permiso_id', $id)->first()->permiso_id == 2 && $moduloPermisos->where('permiso_id', 3)->first()) {
                                $rolModuloPermiso = RolModuloPermiso::firstOrNew([
                                    'rol_id'            => $rolId, 
                                    'modulo_permiso_id' => $moduloPermisos->where('permiso_id', 3)->first()->modulo_permiso_id
                                ]);
                                $rolModuloPermiso->save();
                            }
                            if ($moduloPermisos->where('modulo_permiso_id', $id)->first()->permiso_id == 4 && $moduloPermisos->where('permiso_id', 5)->first()) {
                                $rolModuloPermiso = RolModuloPermiso::firstOrNew([
                                    'rol_id'            => $rolId, 
                                    'modulo_permiso_id' => $moduloPermisos->where('permiso_id', 5)->first()->modulo_permiso_id
                                ]);
                                $rolModuloPermiso->save();
                            }
                        } 
                    }
                }
            });
        } catch (QueryExecption $e) {
            AppController::alertError($request, $e);
            return redirect()->back();
        }

        AppController::alertSuccess($request, 'Los datos se han almacenados correctamente.');
        return redirect()->route('admin.roles.index');
    }
}
