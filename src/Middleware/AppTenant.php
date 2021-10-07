<?php
namespace Hcode\Project\Middleware;

use Config;
use Closure;
use App\Models\Tenant\Tenant as Tts;

class AppTenant
{

    public function handle($request, Closure $next)
    {
        $dominio = $request->server()['HTTP_HOST'];

        $tenant = Tts::where('dominio', $dominio)->where('activo', 1)->first();

        if (!$tenant) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Empresa inexistente.' . $dominio], 404);
            } else {
                return response()->view('errors::404', ['message' => 'Empresa inexistente.']);
            }
        }
        
        config([
            'database.connections.mysql.tenantid' => $tenant->tenant_id,
            'database.connections.mysql.host'     => $tenant->db_host,
            'database.connections.mysql.database' => $tenant->db,
            'database.connections.mysql.username' => $tenant->db_username,
            'database.connections.mysql.password' => $tenant->db_password,
        ]);

        return $next($request);
    }
}
