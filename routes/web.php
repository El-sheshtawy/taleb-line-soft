<?php

use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\Admin\AdminController;
 use App\Http\Controllers\Admin\AdminsController;
 use App\Http\Controllers\Admin\SchoolAccountController;
 use App\Http\Controllers\Admin\FollowUpController;
 use App\Http\Controllers\Admin\SchoolAccountSubscriptionController;
 use App\Http\Controllers\Admin\AcademicYearController;
 
 use App\Http\Controllers\School\SchoolController;
 use App\Http\Controllers\School\ClassRoomController;
 use App\Http\Controllers\School\StudentController;
 use App\Http\Controllers\School\TeacherController as SchoolTeacherController;
 
 use App\Http\Controllers\School\StatisticsController;
 
 use App\Http\Controllers\Teacher\TeacherController;
 use App\Http\Controllers\Teacher\StudentSessionController;
 
 use App\Http\Controllers\Attendance\AttendanceController;
 
 use App\Http\Controllers\File\SchoolFileContoller;
 use App\Http\Controllers\Reminder\SchoolReminderContoller;
 
 use App\Http\Controllers\Public\PublicController;
 use App\Http\Controllers\AuthController;
 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/debug-user', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'user_type' => $user->user_type,
            'has_profile' => $user->profile ? 'Yes' : 'No',
            'profile_type' => $user->profile ? get_class($user->profile) : 'None'
        ]);
    }
    return 'Not logged in';
});
Route::get('/home', function () {
    if (auth()->check()) {
        $userType = auth()->user()->user_type;
        
        if ($userType == 'admin') {
            return redirect()->route('admin.index');
        }
        if ($userType == 'school') {
            return redirect()->route('school.index');
        }
        if (in_array($userType, ['teacher', 'مراقب', 'مشرف'])) {
            return redirect()->route('teacher.index');
        }
        if ($userType == 'student') {
            return redirect()->route('teacher.index'); // or create student dashboard
        }
        if ($userType == 'father') {
            return redirect('/father');
        }
        
        // For unknown user types, show debug info
        return response('Unknown user type: ' . $userType . '. Please contact administrator.', 400);
    }
    return redirect()->route('login');
})->name('temp.redirect'); // this route solve bug of redirect when authentication but should solve using better way
Route::get('/father', function () {
    return view('fathers.fathers');
});
Route::get('/report', function () {
    return view('reports.reports');
});

Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAction'])->name('login-action');
});

Route::middleware('auth')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/admin/export-pdf', [\App\Http\Controllers\Admin\PdfExportController::class, 'exportTable'])->name('admin.export-pdf');
    Route::post('/school/export-pdf', [\App\Http\Controllers\Admin\PdfExportController::class, 'exportTable'])->name('school.export-pdf');
    
    
    Route::prefix('admin')->middleware('user.role:admin')->name("admin.")->group(function(){
        Route::get('/', [AdminController::class, 'index'])->name('index');
        
        Route::resource('admins', AdminsController::class)
                ->only(['store', 'update', 'destroy']);
                
        Route::resource('academic-years', AcademicYearController::class)
                ->only(['store', 'update', 'destroy']);
                
        Route::resource('school-accounts', SchoolAccountController::class)
                ->only(['store', 'update', 'destroy']);
                
                Route::post('{schoolId}/delete-image-byid', [SchoolAccountController::class, 'deleteImageById'])->name('delete-image-byid');


        Route::resource('school-subscriptions', SchoolAccountSubscriptionController::class)
                ->only(['store', 'update', 'destroy']);
        
        Route::resource('follow-ups', FollowUpController::class)
                ->only(['store', 'update', 'destroy']);
                
        Route::resource('special-users', \App\Http\Controllers\Admin\SpecialUserController::class)
                ->only(['store', 'update', 'destroy']);
    });
    
    
    Route::prefix('school')->middleware('user.role:school,teacher,مراقب,مشرف', 'school.subscribed')->name("school.")->group(function () {

        Route::get('/', [SchoolController::class, 'index'])->name('index');
        Route::put('/{schoolAccount}', [SchoolController::class, 'update'])->name('update');
        Route::post('/{schoolAccount}', [SchoolController::class, 'deleteImage'])->name('delete-image');

        Route::resource('classes', ClassRoomController::class)
            ->only(['update', 'destroy']);
        Route::post('classes/store', [ClassRoomController::class, 'store'])->name('classes.store');

        Route::resource('students', StudentController::class)
            ->only(['update', 'destroy']);
        Route::post('students/store', [StudentController::class, 'store'])->name('students.store');
        Route::get('students/delete/selected', [StudentController::class, 'deleteSelected'])->name('students.delete');

        Route::post('students/import', [StudentController::class, 'import'])->name('students.import');

        Route::resource('teachers', SchoolTeacherController::class)
            ->only(['update', 'destroy'])->middleware('check.action');
        Route::post('teachers/store', [SchoolTeacherController::class, 'store'])->name('teachers.store')->middleware('check.action');

        Route::post('teachers/import', [SchoolTeacherController::class, 'import'])->name('teachers.import')->middleware('check.action');
        
        
        Route::get('/statistics/general', [StatisticsController::class, 'index'])->name('statistics.index');
        Route::get('/statistics/school', [StatisticsController::class, 'school'])->name('statistics.school');
        Route::get('/statistics/classes', [StatisticsController::class, 'classes'])->name('statistics.classes');
        Route::get('/statistics/students', [StatisticsController::class, 'students'])->name('statistics.students');
        Route::get('/statistics/table', [StatisticsController::class, 'table'])->name('statistics.table');
    });
    Route::post('school/attendance/delete-day', [StudentSessionController::class, 'deleteDay']);
    
    
    Route::prefix('teacher')->middleware('user.role:teacher,school,مراقب,مشرف', 'school.subscribed')->name("teacher.")->group(function(){
        Route::get('/', [TeacherController::class, 'index'])->name('index');
        
        Route::post('student-sessions', [StudentSessionController::class, 'store'])->name('student-sessions.store')->middleware('check.action');
        Route::get('student-sessions/{sessionNumber}', [StudentSessionController::class, 'getSessionData']);
        Route::delete('student-sessions/{sessionNumber}', [StudentSessionController::class, 'deleteSessionData'])->middleware('check.action');
        Route::post('student-sessions/quick-update', [StudentSessionController::class, 'quickUpdateSession'])->name('student-sessions.quick-update')->middleware('check.action');
        Route::post('/student-sessions/bulk-update', [StudentSessionController::class, 'bulkUpdateSessions'])->name('student-sessions.bulk-update')->middleware('check.action');
    });
    
    Route::get('/students/{student}/sessions', [StudentSessionController::class, 'getDaySessions'])->name('student.day-sessions');
    Route::get('/students/{student}/attendance', [StudentSessionController::class, 'showStudentAttendance'])->name('student.attendance');
    Route::get('/students/{student}/followup-record', [StudentSessionController::class, 'getFollowupRecord'])->name('student.followup.record');
        
    Route::controller(PublicController::class)->prefix('public')->name("public.")->group(function(){
        Route::get('schools/{schoolAccount}/grades/{grade}/classes', 'getGradeClasses')->name('grade-classes');
        Route::get('schools/{schoolAccount}/classes/{class}/students', 'getClassStudents')->name('class-student');
    });
    
    
    Route::prefix('attendance')->middleware('user.role:student,school,teacher,father,admin,مراقب,مشرف')->name("attendance.")->group(function(){
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
    });
    
    Route::prefix('files')->controller(SchoolFileContoller::class)->middleware('user.role:teacher,school,مراقب,مشرف', 'school.subscribed')->name("files.")->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/download/{schoolFile}', 'download')->name('download');
        Route::get('/view/{schoolFile}', 'viewFile')->name('view');
        
        Route::middleware('user.role:school')->group(function(){
            Route::post('/', 'store')->name('store')->middleware('check.action');
            Route::put('/{schoolFile}', 'update')->name('update')->middleware('check.action');
            Route::delete('/{schoolFile}', 'destroy')->name('destroy')->middleware('check.action');
        });
    });
    
    Route::prefix('reminders')->controller(SchoolReminderContoller::class)->middleware('user.role:teacher,school,مراقب,مشرف', 'school.subscribed')->name("reminders.")->group(function(){
        Route::get('/', 'index')->name('index');
        
        Route::middleware('user.role:school')->group(function(){
            Route::post('/', 'store')->name('store')->middleware('check.action');
            Route::put('/{schoolReminder}', 'update')->name('update')->middleware('check.action');
            Route::delete('/{schoolReminder}', 'destroy')->name('destroy')->middleware('check.action');
        });
    });
    
});

// Temporary test route
Route::get('/test-classes/{school_id}/{grade_id}', function($school_id, $grade_id) {
    $classes = App\Models\ClassRoom::where('school_id', $school_id)->where('grade_id', $grade_id)->get(['id', 'name']);
    return response()->json($classes);
});



// this is the same code

// 1- same row

//print pdf