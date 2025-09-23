<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Level;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentSession;
use App\Models\StudentDay;
use App\Models\AcademicYear;
use App\Models\FollowUp;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();
        
        // Validate the request
        $validated = $request->validate([
            'date' => 'nullable|date|before_or_equal:'.$today,
            'grade_id' => 'nullable|exists:grades,id',
            'class_id' => 'nullable|exists:classes,id',
            'monitoring' => 'nullable|in:true,false'
        ], [], [
           'date' => 'التاريخ',
           'grade_id' => 'الصف',
           'class_id' => 'الفصل',
           'monitoring' => 'عرض المتابعة'
        ]);
    
        // Set the date with fallback
        $date = $validated['date'] ?? $today;
        
        $user = Auth::user();
        $teacher = $user->user_type == 'teacher' ? $user->profile : null;
        $school = $user->user_type == 'teacher' ? $user->profile->school : $user->profile;
        $teachers = $school->teachers;
        
        $school_absent_item = $school->followUp->getAbsent();
        
        // Check if no filters are provided
        if (!$request->filled('grade_id') && !$request->filled('class_id')) {
            $students = collect(); // Return empty collection
            $days = collect();
            $sessions = collect();
        } else {
            // Base query for students
            $studentsQuery = Student::with(['user', 'classRoom', 'grade', 'school'])
                ->where('school_id', $school->id)
                 ->orderBy('name', 'asc');
            
            // Apply grade filter if provided
            if ($request->filled('grade_id')) {
                $studentsQuery->where('grade_id', $validated['grade_id']);
            }
            
            // Apply class filter if provided
            if ($request->filled('class_id')) {
                $studentsQuery->where('class_id', $validated['class_id']);
            }
            
            if ($request->filled('monitoring') && $request->monitoring === 'true') {
                $studentsQuery->whereHas('days', function($query) use($school_absent_item, $date) {
                    $query->whereDate('date', $date)
                        ->whereHas('sessions', function($q) use($school_absent_item) {
                            $q->whereNotNull('follow_up_item_id');
                        });
                });
            }
            
            $students = $studentsQuery->get();
            
            // Get attendance data only if we have students
            if ($students->isNotEmpty()) {
                $days = StudentDay::with('student')
                    ->whereDate('date', $date)
                    ->whereIn('student_id', $students->pluck('id'))
                    ->get();
                
                $sessions = StudentSession::with(['followUpItem', 'studentDay'])
                    ->whereIn('student_day_id', $days->pluck('id'))
                    ->get()
                    ->groupBy(fn($session) => $session->studentDay->student_id)
                    ->map(fn($studentSessions) => $studentSessions->keyBy('session_number'));
            } else {
                $days = collect();
                $sessions = collect();
            }
        }
        
        // Get other required data
        $followUp = $school->followUp()->with('items')->first();
        $classes = ClassRoom::with('grade')->where('school_id', $school->id)->get();
        $academicYears = AcademicYear::all();
        $grades = Grade::where('level_id', $school->level_id)->get();
        $levels = Level::all();
        
        return view('teacher.teacher', compact(
            'school', 'classes', 'grades', 'academicYears', 
            'levels', 'followUp', 'teacher',
            'teachers', 'students', 
            'sessions', 'days', 'date', 'today'
        ));
    }
}
