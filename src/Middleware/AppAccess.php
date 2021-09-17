<?php

namespace Hcode\Project\Middleware;

use DB;
use Auth;
use Closure;

use Exception;
use Hcode\Project\Http\Controllers\AppController;

class AppAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->activo == false) {
            Auth::logout();
            return redirect()->to('/');
        }
        
        $route    = $request->route()->getName();
        $response = AppController::routePermiso($route, $request)->getData();

        if ($request->responseJson && !$response->acceso) {
            throw new Exception($response->mensaje);
        }
        
        if (!$response->acceso) {
            return response()->view(config('hcode.crud.view.errorGeneric'), ['response' => $response]);
        }
        return $next($request);
    }
}
