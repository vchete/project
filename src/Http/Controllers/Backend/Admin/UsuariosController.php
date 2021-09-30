<?php

namespace App\Http\Controllers\Backend\Admin;

use DB;
use Hash;
use Auth;
use Crypt;
use Validator;
use Illuminate\Http\Request;
use Hcode\Project\Http\Controllers\AppController;
use Hcode\Project\Http\Controllers\AppCrudController;

use App\Models\Auth\Rol;
use App\Models\Auth\Usuario;
use App\Models\Auth\UsuarioRol;

class UsuariosController extends AppCrudController
{
    public function __construct(Request $request)
    {
        $this->setTitle('Usuarios');
        $this->setModel(new Usuario);
        $this->setField(['field'=> 'nombres', 'name' => 'Nombres', 'validate'=>'required']);
        $this->setField(['field'=> 'apellidos', 'name' => 'Apellidos', 'validate'=>'required']);
        $this->setField(['field'=> 'email', 'name' => 'Email', 'validate'=>'required']);
        $this->setField(['field'=> 'activo', 'name' => 'Activo', 'type' => 'bool', 'default' => true]);
        $this->setField(['field'=> 'password', 'name' => 'Contraseña', 'type' => 'password','show' => false,]);

        $this->middleware(function ($request, $next) {
            if (Auth::id() != 1) {
                $this->setWhere('rol_id', '!=', 1);
            }

            return $next($request);
        });

        $this->setModuleAccess('admin.usuarios');
    }

    public function create(Request $request) {
        return $this->edit($request, Crypt::encrypt(0));
    }

    public function edit(Request $request, $rowId) {
        try {
            $rowId = Crypt::decrypt($rowId);
        } catch (Exception $e) {
            AppController::alertError($request);
            return redirect()->back();
        }

        $data = Usuario::with('roles')->find($rowId);
        $roles = Rol::select('rol_id', 'nombre')->orderBy('nombre');

        if (Auth::id() != 1) {
            $roles->where('rol_id', '!=', 1);
        }

        $roles = $roles->get();

        return view('backend.admin.usuarios.edit', ['data' => $data, 'roles' => $roles]);
    }

    public function store(Request $request) {
        return $this->update($request, Crypt::encrypt(0));
    }

    public function update(Request $request, $rowId) {
        try {
            $rowId = Crypt::decrypt($rowId);
        } catch (Exception $e) {
            AppController::alertError($request);
            return redirect()->back();
        }

        $validate = Validator::make($request->all(), [
            'nombres'   => 'required',
            'apellidos' => 'required',
            'email'     => 'required|email',
            'passwordconfirm' => 'same:newPassword'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        if ($rowId == 0) {
            $usuario = new Usuario;
        }
        else {
            $usuario = Usuario::find($rowId);
        }

        try {
            DB::transaction(function () use ($usuario, $request) {
                $usuario->nombres   = $request->nombres;
                $usuario->apellidos = $request->apellidos;
                $usuario->email     = $request->email;
                $usuario->activo    = $request->activo ? true : false;

                if ($request->password) {
                    $usuario->password = Hash::make($request->newPassword);
                }
                $usuario->save();

                $newRoles = $request->input('roles', []);
                $newRoles = array_keys($newRoles);
                UsuarioRol::where('usuario_id', $usuario->usuario_id)->whereNotIn('rol_id', $newRoles)->delete();
                foreach ($newRoles as $id) {
                    $rol = UsuarioRol::firstOrNew(['usuario_id' => $usuario->usuario_id, 'rol_id' => $id]);
                    $rol->save();
                }
            });
        } catch (QueryException $e) {
            AppController::alertError($request);
            return redirect()->back();
        }

        AppController::alertSuccess($request);
        return redirect()->route('admin.usuarios.index');
    }
}
