<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SchoolAccount;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedDate = $request->get('date', now()->format('Y-m-d'));
        $dayName = Carbon::parse($selectedDate)->locale('ar')->dayName;
        
        // Get user's school
        if ($user->user_type == 'school') {
            $school = $user->profile;
        } elseif (in_array($user->user_type, ['teacher', 'مراقب', 'مشرف'])) {
            $school = $user->getSchool();
        } else {
            $school = null;
        }
        
        $students = collect();
        
        if ($school) {
            // Get only students absent today
            $students = Student::where('school_id', $school->id)
                ->with(['grade', 'classRoom'])
                ->whereHas('days', function($query) use ($selectedDate) {
                    $query->where('is_absent', true)
                          ->where('date', $selectedDate);
                })
                ->get()
                ->sortBy([['grade.name', 'asc'], ['classRoom.name', 'asc'], ['name', 'asc']]);
                
            $absentTodayCount = $students->count();
        } else {
            $absentTodayCount = 0;
        }
        
        return view('reports.reports', compact('students', 'selectedDate', 'dayName', 'school', 'absentTodayCount'));
    }
}