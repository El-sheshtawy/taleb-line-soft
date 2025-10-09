<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolAccount;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Imports\StudentImport;
use App\Rules\ClassBelongsToGrade;

use App\Models\Grade;
use App\Models\ClassRoom;


class StudentController extends Controller
{
     public function store(Request $request)
    {
        $user = Auth::user();
        $school = $user->user_type == 'school' ? $user->profile : $user->getSchool();
        if(!in_array($user->user_type, ['school', 'مشرف']) || !$school) return back()->with('error', 'لا يوجد مدارس في السيستم');
        
         $request->validate([
            'name' => 'required|string|max:255',
            'passport_id' => 'required|unique:students|digits:12',
            'gender' => 'required|in:0,1',
            'phone_number' => 'nullable|digits:8',
            'phone_number_2' => 'nullable|digits:8',
            'grade_id' => 'required|exists:grades,id',
            'class_id' => ['required', 'exists:classes,id', new ClassBelongsToGrade($request->grade_id, $school->id)],
            'nationality_id' => 'required|exists:nationalities,id',
            'note' => ['nullable', 'string', 'max:255'],
        ], [], [
    	        'name' => 'الاسم',
    	        'passport_id' => 'الرقم المدني',
    	        'gender' => 'الجنس',
    	        'phone_number' => 'رقم الهاتف',
    	        'phone_number_2' => 'رقم الهاتف 2',
    	        'class_id' => 'الفصل',
    	        'grade_id' => 'الصف',
    	        'nationality_id' => 'الجنسيه',
    	        'note' => 'ملاحظات'
	    ]);
	    
	    DB::beginTransaction();
    	    
    	try{
            $user = User::create([
                'user_type' => 'student',
                'username' => $request->passport_id,
                'password' => Hash::make($request->phone_number ?? $school->students_default_password ?? '123456789'),
                'defualt_password' => Hash::make($school->students_default_password ?? '123456789')
            ]);
        
            Student::create([
            	'name' => $request->name,
                'passport_id' => $request->passport_id,
                'gender' => $request->gender ? true : false,
                'phone_number' => $request->phone_number,
                'phone_number_2' => $request->phone_number_2,
                'note' => $request->note,
                
                'grade_id' => $request->grade_id,
                'class_id' => $request->class_id,
                'school_id' => $school->id ,
                'user_id' => $user->id,
                'nationality_id' => $request->nationality_id
            ]);
    	
    	    DB::commit();
            return redirect()->route('school.index', ['grade_id' => $request->grade_id, 'class_id' => $request->class_id])->with('success', 'تم إضافة الطالب بنجاح');
    	} catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('school.index', ['grade_id' => $request->grade_id, 'class_id' => $request->class_id])->with('error', 'حدث خطأ أثناء إضافة الطالب: ' . $e->getMessage());
        }
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,ods'
        ], [], [
            'file' => 'الملف',
        ]);
    
        DB::beginTransaction();
    
        try {
            if (!$request->hasFile('file')) {
                return back()->withErrors(['file' => 'لم يتم تحميل أي ملف.'])->withInput();
            }
    
            Excel::import(new StudentImport, $request->file('file'));
    
            DB::commit();
            return redirect()->route('school.index')->with('success', 'تم استيراد الطلاب بنجاح');
        } catch (ValidationException $e) {
            DB::rollback(); 
    
            $failures = $e->failures();
            $errors = [];
    
            foreach ($failures as $failure) {
                $errors[] = "خطأ في السطر " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
    
            return back()->withErrors($errors)->withInput();
    
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('school.index')->with('error', 'حدث خطأ أثناء استيراد الطلاب: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Student $student)
    {
        $user = Auth::user();
        $school = $user->user_type == 'school' ? $user->profile : $user->getSchool();
        if(!$school) return back()->with('error', 'لا يوجد مدارس في السيستم');
        if($student->school_id != $school->id) abort(403, "unauthorized action");
        
        // Convert empty nationality_id to null
        if ($request->nationality_id === '') {
            $request->merge(['nationality_id' => null]);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'passport_id' => 'nullable|digits:12|unique:students,passport_id,' . $student->id,
            'gender' => 'required|in:0,1',
            'phone_number' => 'nullable|digits:8',
            'phone_number_2' => 'nullable|digits:8',
            'grade_id' => 'nullable|exists:grades,id',
            'class_id' => ['nullable', 'exists:classes,id', new ClassBelongsToGrade($request->grade_id, $school->id)],
            'nationality_id' => 'nullable|exists:nationalities,id',
            'note' => ['nullable', 'string', 'max:255'],
        ], [], [
    	        'name' => 'الاسم',
    	        'passport_id' => 'الرقم المدني',
    	        'gender' => 'الجنس',
    	        'phone_number' => 'رقم الهاتف',
    	        'phone_number_2' => 'رقم الهاتف 2',
    	        'class_id' => 'الفصل',
    	        'grade_id' => 'الصف',
    	        'nationality_id' => 'الجنسيه',
    	        'note' => 'ملاحظات'
	    ]);
	    
	    DB::beginTransaction();
    	    
    	try{
    	    
	     if ($request->passport_id && ($student->passport_id !== $request->passport_id || $student->phone_number !== $request->phone_number)) {
                $student->user->update([
                    'username' => $request->passport_id,
                    'password' => Hash::make($request->phone_number ?? $school->students_default_password ?? '123456789'),
                ]);
	        }
    	 
            $student->update($validated);
    	
    	    DB::commit();
            return redirect()->route('school.index', ['tab' => 'students-tab'])->with('success', 'تم تعديل بيانات الطالب بنجاح');
    	} catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('school.index', ['tab' => 'students-tab'])->with('error', 'حدث خطأ أثناء تعديل بيانات الطالب: ' . $e->getMessage());
        }
    }

    public function destroy(Student $student)
    {
        $user = Auth::user();
        $school = $user->user_type == 'school' ? $user->profile : $user->getSchool();
        if(!in_array($user->user_type, ['school', 'مشرف']) || !$school) return back()->with('error', 'لا يوجد مدارس في السيستم');
        if($student->school_id != $school->id) abort(403, "unauthorized action");
        
    	$user = $student->user;
        $student->delete();
        $user->delete();

        return redirect()->route('school.index')->with('success', 'تم حذف الطالب بنجاح');
    }
    
    public function deleteSelected(Request $request)
    {
        $user = Auth::user();
        $school = $user->user_type == 'school' ? $user->profile : $user->getSchool();
        if (!in_array($user->user_type, ['school', 'مشرف']) || !$school) return back()->with('error', 'لا يوجد مدارس في السيستم');

        $classes = ClassRoom::with('grade')->where('school_id', $school->id)->get();
        $grades = Grade::where('level_id', $school->level_id)->get();

        $studentsQuery = Student::where('school_id', $school->id);

        if ($request->has('grade_id') && $grades->find($request->grade_id)) {
            $studentsQuery->where('grade_id', $request->input('grade_id'));
        }
        if ($request->has('class_id') && $classes->find($request->class_id)) {
            $studentsQuery->where('class_id', $request->input('class_id'));
        }
        $studentsQuery->delete();

        return redirect()->route('school.index')->with('success', 'تم حذف الطلاب بنجاح');
    }
}
