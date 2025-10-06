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
            $students = Student::where('students.school_id', $school->id)
                ->with(['grade', 'classRoom'])
                ->withCount(['days as total_absences' => function($query) use ($selectedDate) {
                    $query->where('is_absent', true)
                          ->where('date', '<=', $selectedDate);
                }])
                ->join('grades', 'students.grade_id', '=', 'grades.id')
                ->join('classes', 'students.class_id', '=', 'classes.id')
                ->orderBy('grades.name')
                ->orderBy('classes.name')
                ->orderBy('students.name')
                ->select('students.*')
                ->get();
        }
        
        return view('reports.reports', compact('students', 'selectedDate', 'dayName', 'school'));
    }
}