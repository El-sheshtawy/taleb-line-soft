<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolAccount;
use App\Models\Grade;
use App\Models\Level;
use App\Models\FollowUp;
use App\Models\SchoolAccountSubscription;
use App\Models\AcademicYear;
use App\Models\User;
use App\Models\Nationality;
use App\Models\SystemAccessSetting;

class AdminController extends Controller
{
    public function index()
    {
        $schoolAccounts = SchoolAccount::with(['subscription', 'followUp', 'level'])->withCount('students')->orderBy('school_name')->get();
        $grades = Grade::all();
        $levels = Level::all();
        $followUps = FollowUp::with('items')->get();
        $schoolAccountSubscriptions = SchoolAccountSubscription::with('schoolAccount')->get();
        $academicYears = AcademicYear::orderBy('created_at')->get();
        $admins = User::where('user_type', 'admin')->with('admin')->get();
        $specialUsers = User::whereIn('user_type', ['مراقب', 'مشرف'])->with('profile')->get();
        
        // Load school data for each user
        foreach($specialUsers as $user) {
            if($user->school_id) {
                $user->school = SchoolAccount::find($user->school_id);
            } elseif($user->profile && $user->profile->school_id) {
                $user->school = SchoolAccount::find($user->profile->school_id);
            }
        }
        $nationalities = Nationality::all();
        $settings = SystemAccessSetting::first();
        
        return view('admin.admin', compact('schoolAccounts', 'grades', 'levels', 'followUps', 'schoolAccountSubscriptions', 'academicYears', 'admins', 'specialUsers', 'nationalities', 'settings'));
    }
}
