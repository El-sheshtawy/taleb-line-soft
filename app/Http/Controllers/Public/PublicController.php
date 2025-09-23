<?php

namespace App\Http\Controllers\Public;

use App\Models\Grade;
use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use App\Models\SchoolAccount;
use App\Http\Controllers\Controller;

class PublicController extends Controller
{
    function getGradeClasses(SchoolAccount $schoolAccount, Grade $grade)
    {
        $classes = ClassRoom::where('school_id', $schoolAccount->id)
            ->where('grade_id', $grade->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($classes);
    }

    public function getClassStudents(SchoolAccount $schoolAccount, ClassRoom $class)
    {
        $students = Student::where('school_id', $schoolAccount->id)
            ->where('class_id', $class->id)
            ->orderBy('name')
            ->get(['id', 'name', 'note']);

        return response()->json($students);
    }
}
