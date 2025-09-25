<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolAccount;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class SchoolSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if (!$user) abort(Response::HTTP_UNAUTHORIZED, 'غير مصرح بالدخول');

        // Simple check - just allow authenticated users with valid user types
        if (in_array($user->user_type, ['school', 'teacher', 'مراقب', 'مشرف'])) {
            return $next($request);
        }
        
        abort(Response::HTTP_FORBIDDEN, 'هذه الصفحة متاحة فقط للمدارس والمعلمين');
    }
    
    
     protected function hasValidSubscription(SchoolAccount $school): bool
    {
        if (!$school->subscription) {
            return false;
        }
        
        $now = Carbon::now();
        $startDate = Carbon::parse($school->subscription->start_date);
        $endDate = Carbon::parse($school->subscription->end_date);
        
        return $now->between($startDate, $endDate);
    }
}
