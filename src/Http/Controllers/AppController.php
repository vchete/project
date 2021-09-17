<?php

namespace Hcode\Project\Http\Controllers;

use DB;
use Auth;
use Route;

use Illuminate\Http\Request;

use App\Models\Auth\Permiso;
use App\Models\Auth\ModuloPermiso;
use App\Models\Auth\RolModuloPermiso;

/**
 * Funciones Generales
 * @author Valeriano Chete <vjchete@gmail.com>
 * @date 02/11/2019
 */
class AppController
{
    /**
     * alertSuccess
     *
     * @param [type] $request
     * @param [type] $message
     * @param string $type
     * @return void
     */
    public static function alertSuccess($request, $message = null) {
        $request->session()->flash('alert-msj', !$message ? 'El registro sea guardo correctamente.' : $message);
        $request->session()->flash('alert-type', 'success');
    }

    /**
     * alertError
     *
     * @param [type] $request
     * @param [type] $exception
     * @param [type] $message
     * @return void
     */
    public static function alertError($request, $exception = null, $message = null) {
        if ($message) {
            $message = $message;
        }
        else {
            $message = 'Error al guardar el registro.';
        }
        if ($exception) {
            $message .= (env('APP_DEBUG') ? ('<br>Code: '. $exception->getCode(). ' | Message: '. $exception->errorInfo[2]) : '');
        }

        $request->session()->flash('alert-msj', $message);
        $request->session()->flash('alert-type', 'danger');
    }

    /**
     * routePermisos
     *
     * @param [type] $route
     * @param [type] $request
     * @return void
     */
    public static function routePermiso($route, $request = null) {
        $routeFound = collect(Route::getRoutes()->get())
            ->filter(function ($item) use ($route) {
                return $item->getName() == $route;
            })->first();
        
        if (!$routeFound) {
            return self::getAccesoResponseJson(404, 'Página no encontrada', false, $route);
        }

        $arr = explode('.', $route);
        array_pop($arr);
        $routeModulo = implode('.', $arr);

        $moduloFound = ModuloPermiso::select('m.nombre as modulo', 'm.modulo_id')
            ->leftJoin('auth_modulos as m', 'm.modulo_id', 'auth_modulo_permisos.modulo_id')
            ->where('m.nombre', $routeModulo)
            ->first();
            
        if (!$moduloFound) {
            return self::getAccesoResponseJson(404, 'Módulo no encontrado', false, $route);
        }

        $userRoles       = Auth::user()->roles->pluck('rol_id');
        $permisosModulo  = RolModuloPermiso::select(
            'm.nombre as modulo',
            'p.nombre as permiso',
            DB::raw('CONCAT(m.nombre, ".", p.nombre) as route'))
            ->leftJoin('auth_modulo_permisos as mp', 'mp.modulo_permiso_id', 'auth_rol_modulo_permisos.modulo_permiso_id')
            ->leftJoin('auth_modulos as m', 'm.modulo_id', 'mp.modulo_id')
            ->leftJoin('auth_permisos as p', 'p.permiso_id', 'mp.permiso_id')
            ->whereIn('rol_id', $userRoles)
            ->where('m.modulo_id', $moduloFound->modulo_id)
            ->get();
            $permisoFound = $permisosModulo->where('route', $route)->first();

        if (!$permisoFound) {
            $message = $request ? ($request->responseJson ? 'Acción no autorizado' : 'Página no autorizada') : 'Página no autorizada';
            return self::getAccesoResponseJson(401, $message, false, $route);
        }

        return self::getAccesoResponseJson(200, '', true, $permisoFound->route);
    }

    /**
     * getPermisosModulos
     *
     * @param [type] $model
     * @param [type] $only
     * @return void
     */
    public static function getModuleAccess($model, $only = null)
    {
        $permisos = new Permiso;

        if ($only) {
            $permisos = $permisos->whereIn('nombre', $only);
        }

        $permisos = $permisos
            ->pluck('nombre')
            ->mapWithKeys(function ($item) use ($model) {
                $response = self::routePermiso($model . '.' . $item);
                if ($response->getStatusCode() === 200) {
                    $value = true;
                }
                else {
                    $value = false;
                }

                return [$item => $value];
            });
        
        return $permisos;
    }

    /**
     * getAccesoResponseJson
     *
     * @param [type] $codigo
     * @param [type] $mensaje
     * @param [type] $acceso
     * @param [type] $detalle
     * @return void
     */
    private static function getAccesoResponseJson($codigo, $mensaje, $acceso, $detalle = null) 
    {
        return response()->json([
            'codigo'  => $codigo,
            'acceso'  => $acceso,
            'mensaje' => $mensaje,  
            'detalle' => $detalle
        ], $codigo);
    }

    /**
     * responseJson
     *
     * @param [type] $status
     * @param [type] $arr
     * @return void
     */
    public static function responseJson($status = true, $arr) 
    {
        $response = [
            'status'  => null,
            'data'    => null,
            'errors'  => null,
            'message' => '',
        ];

        if (is_array($arr)) {
            $response =  [
                'status'  => ($status === true ? 200 : ($status === false ? 400 : $status)),
                'data'    => isset($arr['data']) ? $arr['data'] : null,
                'errors'  => isset($arr['errors']) ? $arr['errors'] : null,
                'message' => isset($arr['message']) ? $arr['message'] : '',
            ];
        }

        return response()->json($response);
    }
}
