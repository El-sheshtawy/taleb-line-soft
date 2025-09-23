<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolAccount;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Imports\TeachersImport;

class TeacherController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $school = $user->profile;
        if($user->user_type != 'school' || !$school) return back()->with('error', 'لا يوجد مدارس في السيستم');
        
         $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|digits:8',
            'passport_id' => 'required|digits:12|unique:students',
            'subject' => 'required|string|max:255',
            'nationality_id' => 'required|exists:nationalities,id',
            'head_of_department' => 'nullable|in:on,off',
            'supervisor' => 'nullable|in:on,off',
        ], [], [
    	        'name' => 'الاسم',
    	        'passport_id' => 'الرقم المدني',
    	        'phone_number' => 'رقم الهاتف',
    	        'subject' => 'المادة',
    	        'nationality_id' => 'الجنسيه',
    	        'head_of_department' => 'رئيس القسم',
    	        'supervisor' => 'مشرف'
	    ]);
	    
	    DB::beginTransaction();
    	    
    	try{
            $user = User::create([
                'user_type' => 'teacher',
                'username' => $request->passport_id,
                'password' => Hash::make($request->phone_number ?? $school->teachers_default_password ?? '123456789'),
                'defualt_password' => Hash::make($school->teachers_default_password ?? '')
            ]);
        
            Teacher::create([
            	'name' => $request->name,
                'phone_number' => $request->phone_number,
                'subject' => $request->subject,
                'passport_id' => $request->passport_id,
                
                'head_of_department' => $request->filled('head_of_department'),
                'supervisor' => $request->filled('supervisor'),
                
                'school_id' => $school->id ,
                'user_id' => $user->id,
                'nationality_id' => $request->nationality_id,
            ]);
    	
    	    DB::commit();
            return redirect()->route('school.index')->with('success', 'تم إضافة المعلم بنجاح');
    	} catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('school.index')->with('error', 'حدث خطأ أثناء إضافة النعلم: ' . $e->getMessage());
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
    
            Excel::import(new TeachersImport, $request->file('file'));
    
            DB::commit();
    
            return redirect()->route('school.index')->with('success', 'تم استيراد المعلمين بنجاح');
    
        } catch (ValidationException $e) {
            DB::rollback(); 
    
            $failures = $e->failures();
            $errors = [];
    
            foreach ($failures as $failure) {
                $errors[] = "خطأ في السطر " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
    
            return back()->withErrors($errors)->withInput();
    
        } catch (\Exception $e) {
            DB::rollback(); 
    
            return back()->with('error', 'حدث خطأ أثناء استيراد المعلمين: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Teacher $teacher)
    {
        $user = Auth::user();
        $school = $user->profile;
        if($user->user_type != 'school' || !$school) return back()->with('error', 'لا يوجد مدارس في السيستم');
        if($teacher->school_id != $school->id) abor(403, "unauthorized action");
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'passport_id' => 'required|digits:12|unique:teachers,passport_id,' . $teacher->id,
            'phone_number' => 'nullable|digits:8',
            'subject' => 'required|string|max:255',
            'nationality_id' => 'required|exists:nationalities,id',
            'head_of_department' => 'nullable|in:on,off',
            'supervisor' => 'nullable|in:on,off',
        ], [], [
    	        'name' => 'الاسم',
    	        'passport_id' => 'الرقم المدني',
    	        'phone_number' => 'رقم الهاتف',
    	        'nationality_id' => 'الجنسيه',
    	        'head_of_department' => 'رئيس القسم',
    	        'supervisor' => 'مشرف'
	    ]);
	    
	    DB::beginTransaction();
    	    
    	try{
    	 	if ($teacher->passport_id !== $request->passport_id || $teacher->phone_number !== $request->phone_number) {
                $teacher->user->update([
                    'username' => $request->passport_id,
                    'password' => Hash::make($request->phone_number ?? $school->teachers_default_password ?? '123456789'),
                ]);
	        }
	        
            $validated['head_of_department'] = $request->filled('head_of_department');
            $validated['supervisor'] = $request->filled('supervisor');
    	 
            $teacher->update($validated);
    	
    	    DB::commit();
            return redirect()->route('school.index')->with('success', 'تم تعديل بيانات المعلم بنجاح');
    	} catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('school.index')->with('error', 'حدث خطأ أثناء تعديل بيانات المعلم: ' . $e->getMessage());
        }
    }

    public function destroy(Teacher $teacher)
    {
        $user = Auth::user();
        $school = $user->profile;
        if($user->user_type != 'school' || !$school) return back()->with('error', 'لا يوجد مدارس في السيستم');
        if($teacher->school_id != $school->id) abor(403, "unauthorized action");
        
    	$user = $teacher->user;
        $teacher->delete();
        $user->delete();

        return redirect()->route('school.index')->with('success', 'تم حذف المعلم بنجاح');
    }
}
