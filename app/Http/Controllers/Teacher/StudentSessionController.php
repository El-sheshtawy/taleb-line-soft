<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentSession;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\StudentDay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentSessionController extends Controller
{
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $isSupervisor = $user->user_type === 'مشرف';
        
        $validatedData = $request->validate([
            'date' => 'required|date',
            'session_number' => 'required|integer|between:1,7',
            'teacher_id' => $isSupervisor ? 'nullable|exists:teachers,id' : 'required|exists:teachers,id',
            'students' => 'required|array',
            'students.*.follow_up_item_id' => 'nullable|exists:follow_up_items,id',
            'students.*.teacher_note' => 'nullable|string|max:255'
        ]);
        
        $teacher = in_array($user->user_type, ['teacher', 'مراقب', 'مشرف']) ? $user->profile : null;
        $school = $user->getSchool();
        
        if(!$teacher && $request->filled('teacher_id')){
            $teacher = Teacher::find($request->teacher_id);
        }
        $teachers = $school->teachers;
        
        $today = now()->format('Y-m-d');
        $date = $request->input('date', $today);
        if (!strtotime($date) || $date > $today) $date = $today; 
        
        $sessionNumber = $request->input('session_number');
    
        DB::beginTransaction();
        try {
            $processedStudentIds = [];
    
            foreach ($request->input('students', []) as $studentId => $studentData) {
                $student = Student::find($studentId);
                if (!$student || $student->school_id != $school->id) {
                    continue;
                }
                
                $studentDay = StudentDay::updateOrCreate([
                    'date' => $date,
                    'student_id' => $student->id,
                    'school_id' => $school->id
                ]);
                
                $existingSession = StudentSession::where([
                    'student_day_id' => $studentDay->id,
                    'session_number' => $request->input('session_number')
                ])->first();

                if ($existingSession) {
                    $existingSession->update([
                        'follow_up_item_id' => $studentData['follow_up_item_id'] ?? null,
                        'teacher_note' => $studentData['teacher_note']
                    ]);
                } else {
                    StudentSession::create([
                        'teacher_id' => $teacher ? $teacher->id : null,
                        'session_number' => $request->input('session_number'),
                        'follow_up_item_id' => $studentData['follow_up_item_id'],
                        'student_day_id' => $studentDay->id,
                        'teacher_note' => $studentData['teacher_note']
                    ]);
                }
                
                $absentCount = StudentSession::where('student_day_id', $studentDay->id)
                    ->whereHas('followUpItem', function ($query) {
                        $query->where('is_absent', true);
                    })->count();

                $absentThreshold = $school->absence_count ?? config('app.absent_threshold');

                $studentDay->update([
                    'is_absent' => $absentCount >= $absentThreshold
                ]);
                
            }
    
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ متابعة الحصة بنجاح',
                'date' => $date,
                'session_number' => $sessionNumber
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getSessionData(string $sessionNumber, Request $request){
        $today = now()->toDateString();
        $date = $request->input('date', $today);
        if (!strtotime($date) || $date > $today) $date = $today; 
        
        $sessions = StudentSession::with(['studentDay.student', 'followUpItem'])
            ->whereHas('studentDay', function($query) use ($date) {
                $query->whereDate('date', $date);
            })
            ->where('session_number', $sessionNumber)
            ->get()
            ->map(function ($session) {
                return [
                    'student_id' => $session->studentDay->student_id,
                    'session_id' => $session->id,
                    'follow_up_item_id' => $session->follow_up_item_id,
                    'teacher_note' => $session->teacher_note,
                ];
            });
            
        return response()->json($sessions);
    }
    
    
    public function getDaySessions(Student $student, Request $request){
        $days = StudentDay::with('sessions')->where('student_id', $student->id)->get();
        $student->load('grade', 'classRoom');
            
        return view('teacher.student_day_sessions', compact('student', 'days'));
    }
    
    //i add this for the table to get all date sessions for the specific user take care please 
    // public function getStudentSessions(Student $student, Request $request)
    // {
    //     $today = now()->toDateString();
    //     $oneWeekAgo = now()->subWeek()->toDateString(); // Get the date one week ago
    
    //     $date = $request->input('date', $oneWeekAgo); // Default to one week ago
    //     if (!strtotime($date) || $date > $today) $date = $oneWeekAgo;
    
    //     $day = StudentDay::where('student_id', $student->id)->whereDate('date', $date)->first();
    //     $sessions = $day ? $day->sessions()->with('followUpItem', 'teacher')->get() : collect();
    //     $student->load('grade', 'classRoom');
    
    //     return response()->json([
    //         'student' => $student,
    //         'day' => $day,
    //         'sessions' => $sessions,
    //         'date' => $date
    //     ]);
    // }
    // public function showStudentAttendance(Student $student, Request $request)
    // {
    //     $today = now()->toDateString();
    //     $date = $request->input('date', $today);
    
    //     if (!strtotime($date) || $date > $today) {
    //         $date = $today;
    //     }
    
    //     $day = StudentDay::where('student_id', $student->id)->whereDate('date', $date)->first();
    //     $sessions = $day ? $day->sessions()->with('followUpItem', 'teacher')->get() : collect();
    //     $student->load('grade', 'classRoom');
    
    //     return response()->json([
    //         'student'  => $student,
    //         'date'     => $date,
    //         'sessions' => $sessions
    //     ]);
    // }
    
    public function showStudentAttendance(Student $student, Request $request){
        $today = now()->toDateString();
        $date = $request->input('date', $today);
    
        if (!strtotime($date) || $date > $today) {
            $date = $today;
        }
    
        $sessions = StudentSession::whereHas('studentDay', function ($query) use ($student, $today) {
                $query->where('student_id', $student->id) // Ensure it's for the correct student
                      ->whereDate('date', '<=', $today); // Only past or current sessions
            })
            ->with('followUpItem', 'teacher')
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Load additional student details
        $student->load('grade', 'classRoom');
    
        return response()->json([
            'student'  => $student,
            'date'     => $date,
            'sessions' => $sessions
        ]);
    }
    
    public function quickUpdateSession(Request $request){
         $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:255'
        ],[],[
            'student_id' => 'الطالب',
            'date' => 'التاريخ',
            'notes' => 'الملاحظات',
            "notes.*" => 'الملاحظة'
        ]);
    
    
        $user = Auth::user();
        $school = $user->getSchool();
        $teacher = in_array($user->user_type, ['teacher', 'مراقب', 'مشرف']) ? $user->profile : null;
        
        $today = now()->format('Y-m-d');
        $date = $request->input('date', $today);
        if (!strtotime($date) || $date > $today) $date = $today; 
        
        $updatedSessions = [];
        $studentId = $request->student_id;
        
        $studentDay = StudentDay::firstOrCreate([
            'date' => $date,
            'student_id' => $studentId,
            'school_id' => $school->id
        ]);
                
        foreach ($request->notes ?? [] as $sessionNumber => $note) {
            if($note && $note != ''){
                $existingSession = StudentSession::where([
                    'student_day_id' => $studentDay->id,
                    'session_number' => $sessionNumber
                ])->first();
                
    
                if ($existingSession) {
                    // المشرف يملك صلاحية تعديل جميع الحصص
                    if($teacher && $existingSession->teacher_id != $teacher->id && $user->user_type !== 'مشرف'){
                        return response()->json([
                            'success' => false,
                            'message' => 'ليس لديك الصلاحية لتعديل هذه الحصة ' . $sessionNumber . ' لهذا الطالب '
                        ], 500);
                    }
                    $existingSession->update(['teacher_note' => $note]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'لا يمكن تحديث هذه الحصة ' . $sessionNumber . ' لهذا الطالب '
                    ], 500);
                }
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'تم تحديث البيانات بنجاح',
        ]);
    }
    
    public function deleteDay(Request $request){
        $today = now()->toDateString();
        
        $validated = $request->validate([
            'date' => 'required|date|before_or_equal:'.$today,
            'grade_id' => 'required|exists:grades,id',
            'class_id' => 'required|exists:classes,id',
        ], [], [
           'date' => 'التاريخ',
           'grade_id' => 'الصف',
           'class_id' => 'الفصل'
        ]);
        
        $user = Auth::user();
        $school = $user->profile;
        if($user->user_type != 'school' || !$school) return back()->with('error', 'لا يوجد مدارس في السيستم');
        
        $studentIds = Student::where('grade_id', $validated['grade_id'])->where('class_id', $validated['class_id'])->pluck('id')->toArray();
        
        $days = StudentDay::where('school_id', $school->id)->whereDate('date', $request->date)->whereIn('student_id', $studentIds)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'تم حذف البيانات بنجاح',
        ]);
    }
    
    public function getFollowupRecord(Student $student){
        $days = $student->days()
            ->with(['sessions.followUpItem'])
            ->whereHas('sessions.followUpItem')
            ->orderBy('date', 'desc')
            ->get();
    
        $html = view('teacher.followup_components.followup_record_table', [
            'days' => $days,
            'student' => $student
        ])->render();
    
        return response()->json(['html' => $html]);
    }

}
