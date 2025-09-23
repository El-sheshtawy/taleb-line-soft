<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Nationality;
use App\Models\AcademicYear;
use App\Models\FollowUp;
use App\Models\SchoolAccount;
use App\Models\StudentDay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $school = $user->profile;
        if ($user->user_type != 'school' || !$school) return back()->with('error', 'لا يوجد مدارس في السيستم');

        $classes = ClassRoom::with('grade')->where('school_id', $school->id)->get();
        $grades = Grade::where('level_id', $school->level_id)->get();
        $nationalities = Nationality::all();
        $academicYears = AcademicYear::where('status', 'active')->get();
        $followUps = FollowUp::with('items')->get();

        $studentsQuery = Student::with(['user', 'classRoom', 'grade', 'school'])->where('school_id', $school->id);

        if ($request->has('grade_id') && $grades->find($request->grade_id)) $studentsQuery->where('grade_id', $request->input('grade_id'));
        if ($request->has('class_id') && $classes->find($request->class_id)) $studentsQuery->where('class_id', $request->input('class_id'));

        $students = $studentsQuery->orderBy('name', 'asc')->get();

        $today = now()->toDateString();
        $absents = StudentDay::with('student')
            ->whereDate('date', $today)
            ->whereIn('student_id', $students->pluck('id'))
            ->where('is_absent', true)
            ->count();

        $teachersQuery = Teacher::with(['user', 'school'])->where('school_id', $school->id);

        if ($request->has('subject') && $request->subject !== 'all') {
            $subject = $request->input('subject');

            if ($subject === 'head_of_department') {
                $teachersQuery->where('head_of_department', true);
            } elseif ($subject === 'supervisor') {
                $teachersQuery->where('supervisor', true);
            } else {
                $teachersQuery->where('subject', $subject);
            }
        }

        $teachers = $teachersQuery->orderBy('name', 'asc')->get();


        return view('school.school', compact('school', 'classes', 'grades', 'nationalities', 'academicYears', 'teachers', 'students', 'absents', 'followUps'));
    }

    public function update(SchoolAccount $schoolAccount, Request $request)
    {
        // return $request->all();
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'school_banner_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'username' => 'required|string|max:255|unique:school_accounts,username,' . $schoolAccount->id,
            // 'password' => 'required|string|min:6',
            // 'edu_region' => 'nullable|string|max:255',
            // 'follow_up_id' => 'required|exists:follow_ups,id',
            'teachers_default_password' => 'nullable|string|max:255',
            'students_default_password' => 'nullable|string|max:255',
            'absence_count' => 'nullable|integer|min:1|max:7',

            'table_general' => 'nullable|file|mimes:pdf|max:10240',
            'table_classes' => 'nullable|file|mimes:pdf|max:10240',
        ], [], [
            'school_name' => 'اسم المدرسة',
            'password' => 'كلمة المرور',
            'school_logo_url' => 'شعار المدرسة',
            'school_banner_url' => 'لافتة المدرسة',
            'username' => 'اسم المستخدم',
            'edu_region' => 'المنطقة التعليمية',
            'follow_up_id' => 'نظام المتابعة',
            'teachers_default_password' => 'كلمة مرور المعلمين الافتراضية',
            'students_default_password' => 'كلمة مرور الطلاب الافتراضية',
            'absence_count' => 'نصاب الغياب',
            'table_general' => 'الجدول العام',
            'table_classes' => 'جدول الحصص',
        ]);

        DB::beginTransaction();

        try {
            // $validated['username'] = preg_replace('/[^A-Za-z0-9]/', '', trim($validated['username']));

            // if ($validated['username'] !== $schoolAccount->username || $validated['password'] !== $schoolAccount->password) {
            //     $schoolAccount->user->update([
            //         'username' => $request->username,
            //         'password' => Hash::make($request->password),
            //     ]);
            // }

            $schoolAccount->update($validated);

            if ($request->hasFile('school_logo_url')) {
                if ($schoolAccount->school_logo_url) {
                    Storage::delete($schoolAccount->school_logo_url);
                }

                $logoPath = $request->file('school_logo_url')->store('school_logos', 'public');
                $schoolAccount->update(['school_logo_url' => $logoPath]);
            }

            if ($request->hasFile('school_banner_url')) {
                if ($schoolAccount->school_banner_url) {
                    Storage::delete($schoolAccount->school_banner_url);
                }

                $logoPath = $request->file('school_banner_url')->store('school_banners', 'public');
                $schoolAccount->update(['school_banner_url' => $logoPath]);
            }

            if ($request->hasFile('table_general')) {
                // Delete the old file if it exists
                if ($schoolAccount->table_general) {
                    Storage::delete($schoolAccount->table_general);
                }

                $file = $request->file('table_general');
                $originalName = $file->getClientOriginalName();
                $generalPath = $file->storeAs('school_tables/general', $originalName, 'public');

                $schoolAccount->update(['table_general' => $generalPath]);
            }
            if ($request->hasFile('table_classes')) {
                if ($schoolAccount->table_classes) {
                    Storage::delete($schoolAccount->table_classes);
                }

                $file = $request->file('table_classes');
                $originalName = $file->getClientOriginalName();
                $classesPath = $file->storeAs('school_tables/classes', $originalName, 'public');
                $schoolAccount->update(['table_classes' => $classesPath]);
            }


            DB::commit();
            return redirect()->route('school.index')->with('success', 'تم تعديل بيانات حساب المدرسة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('school.index')->with('error', 'حدث خطأ أثناء تعديل بيانات المدرسة: ' . $e->getMessage());
        }
    }

    public function deleteImage(SchoolAccount $schoolAccount, Request $request)
    {
        $validated = $request->validate([
            'image_type' => 'required|in:logo,banner'
        ]);


        DB::beginTransaction();

        try {

            if ($request->image_type == 'logo' && $schoolAccount->school_logo_url) {
                Storage::delete($schoolAccount->school_logo_url);
                $schoolAccount->update(['school_logo_url' => null]);
            }

            if ($request->image_type == 'banner' && $schoolAccount->school_banner_url) {
                Storage::delete($schoolAccount->school_banner_url);
                $schoolAccount->update(['school_banner_url' => null]);
            }

            DB::commit();
            return redirect()->route('school.index')->with('success', 'تم حذف صورة حساب المدرسة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('school.index')->with('error', 'حدث خطأ أثناء حذف صور المدرسة: ' . $e->getMessage());
        }
    }
}
