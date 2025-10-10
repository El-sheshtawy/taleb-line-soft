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
            if ($request->get('show_all_absences')) {
                // Get all students with more than 0 total absences
                $students = Student::where('school_id', $school->id)
                    ->with(['grade', 'classRoom'])
                    ->withCount(['days as total_absences' => function($query) {
                        $query->where('is_absent', true);
                    }])
                    ->whereHas('days', function($query) {
                        $query->where('is_absent', true);
                    })
                    ->get()
                    ->filter(function($student) {
                        return $student->total_absences > 0;
                    })
                    ->sortBy([['grade.name', 'asc'], ['classRoom.name', 'asc'], ['name', 'asc']]);
                    
                $absentTodayCount = 0; // Not applicable for all absences view
            } else {
                // Get only students absent today (default behavior)
                $students = Student::where('school_id', $school->id)
                    ->with(['grade', 'classRoom'])
                    ->withCount(['days as total_absences' => function($query) use ($selectedDate) {
                        $query->where('is_absent', true)
                              ->where('date', '<=', $selectedDate);
                    }])
                    ->whereHas('days', function($query) use ($selectedDate) {
                        $query->where('is_absent', true)
                              ->where('date', $selectedDate);
                    })
                    ->get()
                    ->sortBy([['grade.name', 'asc'], ['classRoom.name', 'asc'], ['name', 'asc']]);
                    
                $absentTodayCount = $students->count();
            }
        } else {
            $absentTodayCount = 0;
        }
        
        return view('reports.reports', compact('students', 'selectedDate', 'dayName', 'school', 'absentTodayCount'));
    }
}