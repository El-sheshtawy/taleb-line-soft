<?php

namespace App\Http\Controllers\School;

use Carbon\Carbon;
use App\Models\Grade;
use App\Models\Level;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\StudentDay;
use App\Models\AcademicYear;

use Illuminate\Http\Request;
use App\Models\StudentSession;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();

        // Validate the request
        $validated = $request->validate([
            'date' => 'nullable|date|before_or_equal:' . $today,
        ], [], [
            'date' => 'التاريخ',
        ]);

        // Set the date with fallback
        $date = $validated['date'] ?? $today;

        $user = Auth::user();
        $school = $user->getSchool();

        // First get all classes
        $classes = ClassRoom::with('grade')
            ->where('school_id', $school->id)
            ->orderBy('grade_id')
            ->orderBy('name')
            ->get();

        // Then get all students in these classes with their attendance data
        $students = Student::with([
            'classRoom',
            'days' => function ($q) use ($date) {
                $q->whereDate('date', $date);
            },
            'days.sessions' => function ($q) {
                $q->with('teacher', 'followUpItem');
            }
        ])
            ->whereIn('class_id', $classes->pluck('id'))
            ->get();

        // Group students by class
        $studentsByClass = $students->groupBy('class_id');

        // Prepare statistics data
        $statistics = [];
        $sessionCount = 7; // Assuming 7 sessions per day

        foreach ($classes as $class) {
            $classStudents = $studentsByClass->get($class->id, collect());

            $classStats = [
                'class' => $class,
                'sessions' => []
            ];

            // Get all sessions for this class's students
            $allSessions = collect();
            foreach ($classStudents as $student) {
                foreach ($student->days as $day) {
                    $allSessions = $allSessions->merge($day->sessions);
                }
            }

            // Organize by session number
            for ($i = 1; $i <= $sessionCount; $i++) {
                $sessionSessions = $allSessions->where('session_number', $i);

                $teachers = $sessionSessions->groupBy(function ($session) {
                    return $session->teacher ? $session->teacher->id : 'unknown';
                })->map(function ($sessions) {
                    return [
                        'teacher' => $sessions->first()->teacher,
                        'absent_count' => $sessions->where('followUpItem.is_absent', true)->count()
                    ];
                });

                $classStats['sessions'][$i] = [
                    'teachers' => $teachers,
                    'total_absent' => $sessionSessions->where('followUpItem.is_absent', true)->count()
                ];
            }

            $statistics[] = $classStats;
        }

        return view('school.statistics.general.index', compact(
            'school',
            'statistics',
            'date',
            'today',
            'sessionCount'
        ));
    }

    public function students(Request $request)
    {
        $user = Auth::user();
        $school = $user->getSchool();
        $grades = Grade::where('level_id', $school->level_id)->get();
        $classes = ClassRoom::with('grade')->where('school_id', $school->id)->get();

        // Initialize variables
        $student = null;
        $attendanceRecords = collect();
        $schoolAbsentItem = $school->followUp->items->where('is_absent', true)->first();

        if ($request->filled(['grade_id', 'class_id', 'student_id'])) {
            $validated = $request->validate([
                'grade_id' => 'required|exists:grades,id',
                'class_id' => 'required|exists:classes,id',
                'student_id' => 'required|exists:students,id',
                'from' => 'nullable|date',
                'to' => 'nullable|date|required_with:from|after_or_equal:from'
            ]);

            // Get the student
            $student = Student::with(['user', 'grade', 'classRoom'])
                ->where('id', $validated['student_id'])
                ->where('school_id', $school->id)
                ->firstOrFail();

            // Get attendance records with conditional date filtering
            $attendanceRecords = StudentDay::with(['sessions' => function ($query) {
                $query->with('followUpItem')->orderBy('session_number');
            }])
                ->whereHas('sessions.followUpItem')
                ->where('student_id', $student->id)
                ->when($request->filled(['from', 'to']), function ($query) use ($request) {
                    $query->whereBetween('date', [
                        Carbon::parse($request->from),
                        Carbon::parse($request->to)
                    ]);
                })
                ->orderBy('date', 'desc')
                ->get()
                ->keyBy(function ($item) {
                    return Carbon::parse($item->date)->format('Y-m-d');
                });
        }

        return view('school.statistics.students.index', [
            'school' => $school,
            'grades' => $grades,
            'classes' => $classes,
            'student' => $student,
            'attendanceRecords' => $attendanceRecords,
            'schoolAbsentItem' => $schoolAbsentItem
        ]);
    }

    public function classes(Request $request)
    {
        $today = now()->toDateString();

        // Validate the request
        $validated = $request->validate([
            'from' => 'nullable|date|before_or_equal:' . $today,
            'to' => 'nullable|date|after_or_equal:from|before_or_equal:' . $today,
            'grade_id' => 'nullable|exists:grades,id',
            'class_id' => 'nullable|exists:classes,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'monitoring' => 'nullable|in:true,false'
        ], [], [
            'from' => 'التاريخ من',
            'to' => 'التاريخ إلى',
            'grade_id' => 'الصف',
            'class_id' => 'الفصل',
            'teacher_id' => 'المعلم',
            'monitoring' => 'عرض المتابعة'
        ]);

        // Set the date range with fallback
        $from = $validated['from'] ?? $today;
        $to = $validated['to'] ?? $from;

        // Create date range array
        $dates = [];
        $currentDate = Carbon::parse($from);
        $endDate = Carbon::parse($to);

        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->toDateString();
            $currentDate->addDay();
        }

        $user = Auth::user();
        $teacher = in_array($user->user_type, ['teacher', 'مراقب', 'مشرف']) ? $user->profile : null;
        $school = $user->getSchool();
        $teachers = $school->teachers;

        $school_absent_item = $school->followUp->getAbsent();

        // Check if no filters are provided
        if (!$request->filled('grade_id') && !$request->filled('class_id')) {
            $studentsData = collect(); // Return empty collection
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

            $students = $studentsQuery->get();

            // Get attendance data for each date
            $studentsData = collect();

            foreach ($dates as $date) {
                $days = collect();
                $sessions = collect();

                if ($students->isNotEmpty()) {
                    $days = StudentDay::with('student')
                        ->whereDate('date', $date)
                        ->whereIn('student_id', $students->pluck('id'))
                        ->get();

                    $sessions = StudentSession::with(['followUpItem', 'studentDay', 'teacher'])
                        ->whereIn('student_day_id', $days->pluck('id'))
                        ->when($request->filled('teacher_id'), function ($q) use ($validated) {
                            $q->where('teacher_id', $validated['teacher_id']);
                        })
                        ->get()
                        ->groupBy(fn($session) => $session->studentDay->student_id)
                        ->map(fn($studentSessions) => $studentSessions->keyBy('session_number'));
                }

                $studentsData->push([
                    'date' => $date,
                    'students' => $students,
                    'days' => $days,
                    'sessions' => $sessions
                ]);
            }
        }

        // Get other required data
        $followUp = $school->followUp()->with('items')->first();
        $classes = ClassRoom::with('grade')->where('school_id', $school->id)->get();
        $academicYears = AcademicYear::all();
        $grades = Grade::where('level_id', $school->level_id)->get();
        $levels = Level::all();
        
        $class = ClassRoom::find($request->class_id);
        $grade = Grade::find($request->grade_id);

        return view('school.statistics.classes.index', compact(
            'school',
            'classes',
            'grades',
            'academicYears',
            'levels',
            'followUp',
            'teacher',
            'teachers',
            'studentsData',
            'from',
            'to',
            'today',

            'class',
            'grade',
        ));
    }


    public function school(Request $request)
    {
        $today = now()->toDateString();

        // Validate the request
        $validated = $request->validate([
            'date' => 'nullable|date|before_or_equal:' . $today,
        ], [], [
            'date' => 'التاريخ',
        ]);

        // Set the date with fallback
        $date = $validated['date'] ?? $today;

        $user = Auth::user();
        $school = $user->getSchool();

        // First get all classes
        $classes = ClassRoom::with('grade')
            ->where('school_id', $school->id)
            ->withCount('students')
            ->withCount(['students as absent_students' => function ($query) use ($date) {
                $query->whereHas('days', function ($q) use ($date) {
                    $q->whereDate('date', $date)->where('is_absent', true);
                });
            }])
            ->get();

        return view('school.statistics.school.index', compact(
            'school',
            'date',
            'classes',
        ));
    }

    public function table()
    {
        $user = Auth::user();
        $school = $user->getSchool();
        $table = $school->table_general;
        return view('school.statistics.table.index', compact('table', 'school'));
    }
}
