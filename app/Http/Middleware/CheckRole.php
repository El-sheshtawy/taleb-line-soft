<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        $user = Auth::user();
        
        if (!$user || !in_array($user->user_type, $types)) {
            abort(403, 'Unauthorized access');
        }
        
        // Allow مراقب and مشرف users without profiles if they have school_id
        if (in_array($user->user_type, ['مراقب', 'مشرف']) && $user->school_id) {
            return $next($request);
        }
        
        // For other user types, require profile
        if (!in_array($user->user_type, ['مراقب', 'مشرف']) && !$user->profile) {
            abort(403, 'Unauthorized access');
        }
        
        return $next($request);
    }
}
