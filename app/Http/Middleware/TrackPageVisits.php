<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageVisit;
use Illuminate\Support\Facades\View;

class TrackPageVisits
{
    public function handle(Request $request, Closure $next)
    {
        // Solo contar peticiones GET que no sean AJAX ni assets
        if ($request->method() === 'GET' && !$request->ajax() && !$request->is('api/*')) {
            $pageUrl = $request->path();
            
            // Excluir rutas de autenticación y assets
            $excluded = ['login', 'logout', 'register', 'password'];
            
            if (!in_array($pageUrl, $excluded)) {
                $pageVisit = PageVisit::firstOrCreate(['page_url' => $pageUrl]);
                $pageVisit->increment('visit_count');
                View::share('pageVisitCount', $pageVisit->visit_count);
            } else {
                View::share('pageVisitCount', 0);
            }
        } else {
            // Para POST/AJAX, asegurar que la variable exista
            $shared = View::getShared();
            if (!array_key_exists('pageVisitCount', $shared)) {
                View::share('pageVisitCount', 0);
            }
        }

        return $next($request);
    }
}