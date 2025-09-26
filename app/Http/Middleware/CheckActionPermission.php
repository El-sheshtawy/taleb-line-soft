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
        
        // مراقب role - allow read-only access to school routes
        if ($user->user_type === 'مراقب') {
            $currentRoute = $request->route()->getName() ?? '';
            $currentPath = $request->path();
            if (!str_contains($currentRoute, 'school') && !str_contains($currentPath, 'school')) {
                abort(403, 'غير مسموح لك بتنفيذ هذا الإجراء');
            }
        }
        
        // مشرف role - allow access to teacher and school routes
        if ($user->user_type === 'مشرف') {
            $currentRoute = $request->route()->getName() ?? '';
            $currentPath = $request->path();
            if (!str_contains($currentRoute, 'teacher') && !str_contains($currentRoute, 'school') && !str_contains($currentPath, 'teacher') && !str_contains($currentPath, 'school')) {
                abort(403, 'غير مسموح لك بتنفيذ هذا الإجراء');
            }
        }
        
        return $next($request);
    }
}