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

        if ($user->user_type === 'school') {
            if (!$user->profile) abort(Response::HTTP_FORBIDDEN, 'لا يوجد حساب مدرسة مرتبط بهذا المستخدم');

            if (!$this->hasValidSubscription($user->profile)) abort(Response::HTTP_FORBIDDEN, 'انتهت صلاحية اشتراك المدرسة أو غير موجود');
        }
        elseif (in_array($user->user_type, ['teacher', 'مراقب', 'مشرف'])) {
            $school = null;
            
            // For مراقب and مشرف users with school_id
            if (in_array($user->user_type, ['مراقب', 'مشرف']) && $user->school_id) {
                $school = SchoolAccount::find($user->school_id);
            }
            // For teachers with profile
            elseif ($user->profile && $user->profile->school) {
                $school = $user->profile->school;
            }
            
            if (!$school) abort(Response::HTTP_FORBIDDEN, 'المعلم غير مرتبط بمدرسة أو لا يوجد حساب مدرسة');

            if (!$this->hasValidSubscription($school)) abort(Response::HTTP_FORBIDDEN, 'انتهت صلاحية اشتراك المدرسة أو غير موجود');
        }
        else {
            abort(Response::HTTP_FORBIDDEN, 'هذه الصفحة متاحة فقط للمدارس والمعلمين');
        }

        return $next($request);
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
