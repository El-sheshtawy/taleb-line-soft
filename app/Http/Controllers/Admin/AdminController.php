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
        $admins = User::where('user_type', 'admin')->get();
        $specialUsers = User::whereIn('user_type', ['مراقب', 'مشرف'])->get();
        $nationalities = Nationality::all();
        
        return view('admin.admin', compact('schoolAccounts', 'grades', 'levels', 'followUps', 'schoolAccountSubscriptions', 'academicYears', 'admins', 'specialUsers', 'nationalities'));
    }
}
