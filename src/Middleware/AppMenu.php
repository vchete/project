<?php

namespace Hcode\Project\Middleware;

use Auth;
use Route;
use Closure;

use App\Models\Auth\Menu;
use App\Models\Auth\RolModuloPermiso;

class AppMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    private $html = '';
    private $menuData = [];
    private $modulosPermisos;

    public function handle($request, Closure $next)
    {
        if (!$request->session()->has('menuData')) {
            $roles                 = Auth::user()->roles()->pluck('rol_id');
            $this->modulosPermisos = RolModuloPermiso::whereIn('rol_id', $roles)->distinct('modulo_permiso_id')->pluck('modulo_permiso_id')->toArray();
            $this->getMenuData();
            $menuData = Collect($this->menuData)->filter(function ($item) {
                return $item->modulo_permiso_id || !empty($item->hijos);
            });
            $request->session()->put('menuData', $menuData);
        }

        $this->getMenuHtml($request, $request->session()->get('menuData'));
        $request->session()->put('hcMenu', $this->html);
        
        return $next($request);
    }

    private function getMenuData() {
        $items = Menu::with([
                'moduloPermiso.modulo' => function ($q) {
                    $q->select('modulo_id', 'nombre');
                }, 
                'moduloPermiso.permiso' => function ($q) {
                    $q->select('permiso_id', 'nombre');
                }
            ])
            ->select('menu_id', 'padre_id', 'modulo_permiso_id', 'nombre', 'icono')
            ->where('padre_id', null)
            ->orderBy('orden', 'asc')
            ->get()
            ->filter(function ($item) {
                return $item->modulo_permiso_id == null || in_array($item->modulo_permiso_id, $this->modulosPermisos);
            });
        foreach ($items as $index => $menu) {
            $this->menuData[$menu->menu_id] = $menu;
            $this->menuData[$menu->menu_id]->hijos = $this->getMenuDataHijos($menu->menu_id);
        }
    }

    private function getMenuDataHijos ($papaId)
    {
        $hijos = Menu::with([
            'moduloPermiso.modulo' => function ($q) {
                $q->select('modulo_id', 'nombre');
            }, 
            'moduloPermiso.permiso' => function ($q) {
                $q->select('permiso_id', 'nombre');
            }
        ])
        ->select('menu_id', 'padre_id', 'modulo_permiso_id', 'nombre', 'icono')
        ->where('padre_id', $papaId)
        ->orderBy('orden', 'asc')
        ->get()
        ->filter(function ($item) {
            return $item->modulo_permiso_id == null || in_array($item->modulo_permiso_id, $this->modulosPermisos);
        });

        $arr = [];
        foreach ($hijos as $index => $menu) {
            $arr[$menu->menu_id] = $menu;
            $arr[$menu->menu_id]->hijos = $this->getMenuDataHijos($menu->menu_id);
        }

        return $arr;
    }

    private function getMenuHtml($request, $items) {
        foreach ($items as $menu) {
            if (empty($menu->hijos)) {
                if ($menu->modulo_permiso_id) {
                    $route       = $menu->moduloPermiso->modulo->nombre . '.' . $menu->moduloPermiso->permiso->nombre;
                    $classActive = $request->route()->getController()->getModule() == $menu->moduloPermiso->modulo->nombre ? 'active' : '';
                    
                    $this->html .= '<li class="nav-item">';
                    $this->html .= '<a class="nav-link '. $classActive .'" href="' . route($route) . '">
                        '.($menu->icono ? ('<i class="' . $menu->icono . '"></i> ') : '&nbsp;&nbsp;&nbsp;').'
                        <p>'.$menu->nombre .'</p>
                    </a>';
                }
            }
            else {
                $this->html .= '<li class="nav-item has-treeview">';
                $this->html .= '<a href="javascript: void(0);" class="nav-link">
                    '.($menu->icono ? ('<i class="' . $menu->icono . '"></i> ') : '').'
                    <p>'.$menu->nombre .'<i class="right fas fa-angle-left"></i></p>
                </a>';

                $this->html .= '<ul class="nav nav-treeview">';
                $this->getMenuHtml($request, $menu->hijos);
                $this->html .= '</ul>';
            }
            $this->html .= '</li>';
        }
    }
}
