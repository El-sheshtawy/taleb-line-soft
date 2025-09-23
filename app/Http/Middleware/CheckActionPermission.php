<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckActionPermission
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if (!$user) {
            abort(403, 'Unauthorized access');
        }
        
        // مراقب role - no actions allowed
        if ($user->user_type === 'مراقب') {
            abort(403, 'غير مسموح لك بتنفيذ هذا الإجراء');
        }
        
        // مشرف role - only actions on teacher routes allowed
        if ($user->user_type === 'مشرف') {
            $currentRoute = $request->route()->getName();
            if (!str_contains($currentRoute, 'teacher.') && !str_contains($request->path(), 'teacher')) {
                abort(403, 'غير مسموح لك بتنفيذ هذا الإجراء');
            }
        }
        
        return $next($request);
    }
}