<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolAccount;
use App\Models\Student;
use App\Models\Grade;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'nullable|exists:school_accounts,id',
            'grade_id' => 'nullable|exists:grades,id',
            'class_id' => 'nullable|exists:classes,id',
        ], [], [
            'school_id' => 'المدرسة',
            'grade_id' => 'الصف',
            'class_id' => 'الفصل',
        ]);

        $school = $this->getUserSchool($request);
        
        $students = $this->getFilteredStudents($school, $validated);
        
        [$schools, $grades, $classes] = $this->getDropdownOptions($school);
        
        return view('attendance.index', compact(
            'schools', 
            'grades', 
            'classes', 
            'students',
            'school'
        ));
    }

    protected function getUserSchool(Request $request)
    {
        $user = Auth::user();
        
        if ($user->user_type == 'school') {
            return $user->profile;
        }
        
        if ($user->user_type == 'teacher') {
            return $user->profile->school;
        }
        
        if ($request->filled('school_id')) {
            return SchoolAccount::findOrFail($request->school_id);
        }
        
        return null;
    }

    protected function getFilteredStudents($school, $validated)
    {
        if ( !$school || empty($validated['grade_id']) || empty($validated['class_id'])) {
            return collect();
        }

        return Student::where('school_id', $school->id)
            ->where('grade_id', $validated['grade_id'])
            ->where('class_id', $validated['class_id'])
            ->withCount(['days as absences_count' => function($query) {
                $query->where('is_absent', true);
            }])
            ->with('grade', 'classRoom')
            ->orderBy('name')
            ->get();
    }

    protected function getDropdownOptions($school)
    {
        $schools = SchoolAccount::orderBy('school_name')->get();
        
        $grades = $school 
            ? Grade::where('level_id', $school->level_id)->orderBy('name')->get()
            : Grade::orderBy('name')->get();
            
        $classes = $school && request('grade_id')
            ? $school->classes()->where('grade_id', request('grade_id'))->orderBy('name')->get()
            : collect();

        return [$schools, $grades, $classes];
    }
}