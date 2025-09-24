<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolAccount;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class SchoolAccountController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'school_banner_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'username' => 'required|string|max:255|unique:school_accounts',
            'password' => 'required|string|min:6',
            'subscription_state' => 'nullable|in:active,inactive',
            'edu_region' => 'nullable|string|max:255',
            'teachers_default_password' => 'nullable|string|max:255',
            'students_default_password' => 'nullable|string|max:255',
            'absence_count' => 'nullable|integer|min:1|max:7',
            'follow_up_id' => 'required|exists:follow_ups,id',
            'level_id' => 'nullable|exists:levels,id',
            'viewer_name' => 'nullable|string|max:255',
            'viewer_password' => 'nullable|string|max:255',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_password' => 'nullable|string|max:255'
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
	        'absence_count' => 'نصاب الغياب'
	    ]);
	    
	    DB::beginTransaction();
    	    
    	try{
    	    $validated['username'] = preg_replace('/[^A-Za-z0-9]/', '', trim($validated['username']));
	    
            if ($request->hasFile('school_logo_url')) {
                $logoPath = $request->file('school_logo_url')->store('school_logos', 'public');
                $validated['school_logo_url'] = $logoPath;
            }
            if ($request->hasFile('school_banner_url')) {
                $logoPath = $request->file('school_banner_url')->store('school_banners', 'public');
                $validated['school_banner_url'] = $logoPath;
            }
    
            $user = User::create([
                'user_type' => 'school',
                'username' => $validated['username'],
                'password' => Hash::make($validated['password'])
            ]);
            
            $schoolAccount = SchoolAccount::create([
                'school_name' => $validated['school_name'],
                'school_logo_url' => $validated['school_logo_url'] ?? null,
                'school_banner_url' => $validated['school_banner_url'] ?? null,
                'username' => $validated['username'],
                'password' => $validated['password'],
                'subscription_state' => $validated['subscription_state'],
                'edu_region' => $validated['edu_region'],
                'follow_up_id' => $validated['follow_up_id'],
                'teachers_default_password' => $validated['teachers_default_password'],
                'students_default_password' => $validated['students_default_password'],
                'absence_count' => $validated['absence_count'] ?? 2,
                'level_id' => $validated['level_id'],
                'user_id' => $user->id,
                'viewer_name' => $validated['viewer_name'],
                'viewer_password' => $validated['viewer_password'],
                'supervisor_name' => $validated['supervisor_name'],
                'supervisor_password' => $validated['supervisor_password'],
            ]);
            

    	
             DB::commit();
            return redirect()->route('admin.index')->with('success', 'تم إنشاء حساب المدرسة بنجاح');
    	} catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('admin.index')->with('error', 'حدث خطأ أثناء إضافة المدرسة: ' . $e->getMessage());
        }
    }

    public function update(Request $request, SchoolAccount $schoolAccount)
    {
        $validated = $request->validate([
            'school_name' => 'sometimes|string|max:255',
            'school_logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'username' => 'sometimes|string|max:255|unique:school_accounts,username,' . $schoolAccount->id,
            'password' => 'nullable|string|min:6',
            'subscription_state' => 'sometimes|in:active,inactive',
            'edu_region' => 'nullable|string|max:255',
            'follow_up_id' => 'required|exists:follow_ups,id',
            'teachers_default_password' => 'nullable|string|max:255',
            'students_default_password' => 'nullable|string|max:255',
            'absence_count' => 'nullable|integer|min:1|max:7',
            'level_id' => 'nullable|exists:levels,id',
            'viewer_name' => 'nullable|string|max:255',
            'viewer_password' => 'nullable|string|max:255',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_password' => 'nullable|string|max:255'
        ], [], [
	        'school_name' => 'اسم المدرسة',
	        'password' => 'كلمة المرور',
	        'school_logo_url' => 'شعار المدرسة',
	        'username' => 'اسم المستخدم',
	        'edu_region' => 'المنطقة التعليمية',
	        'follow_up_id' => 'نظام المتابعة',
	        'teachers_default_password' => 'كلمة مرور المعلمين الافتراضية',
	        'students_default_password' => 'كلمة مرور الطلاب الافتراضية',
	        'absence_count' => 'نصاب الغياب'
	    ]);
	    
	       
	    DB::beginTransaction();
	    
    	try{
    	    $validated['username'] = preg_replace('/[^A-Za-z0-9]/', '', trim($validated['username']));
    	    
    	    if ($validated['username'] !== $schoolAccount->username || $validated['password'] !== $schoolAccount->password) {
                $schoolAccount->user->update([
                    'username' => $request->username,
	                'password' => Hash::make($request->password),
                ]);
	        }
    	    
            $schoolAccount->update($validated);
            
            // Update or create viewer user if provided
            if (!empty($validated['viewer_name']) && !empty($validated['viewer_password'])) {
                $viewerUser = User::where('user_type', 'مراقب')
                    ->where('school_id', $schoolAccount->id)
                    ->first();
                    
                if ($viewerUser) {
                    $viewerUser->update([
                        'username' => $validated['viewer_name'],
                        'password' => Hash::make($validated['viewer_password'])
                    ]);
                } else {
                    User::create([
                        'user_type' => 'مراقب',
                        'username' => $validated['viewer_name'],
                        'password' => Hash::make($validated['viewer_password']),
                        'school_id' => $schoolAccount->id
                    ]);
                }
            }
            
            // Update or create supervisor user if provided
            if (!empty($validated['supervisor_name']) && !empty($validated['supervisor_password'])) {
                $supervisorUser = User::where('user_type', 'مشرف')
                    ->where('school_id', $schoolAccount->id)
                    ->first();
                    
                if ($supervisorUser) {
                    $supervisorUser->update([
                        'username' => $validated['supervisor_name'],
                        'password' => Hash::make($validated['supervisor_password'])
                    ]);
                } else {
                    User::create([
                        'user_type' => 'مشرف',
                        'username' => $validated['supervisor_name'],
                        'password' => Hash::make($validated['supervisor_password']),
                        'school_id' => $schoolAccount->id
                    ]);
                }
            }
            
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
        
    	    DB::commit();
            return redirect()->route('admin.index')->with('success', 'تم تعديل بيانات حساب المدرسة بنجاح');
    	} catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('admin.index')->with('error', 'حدث خطأ أثناء تعديل بيانات المدرسة: ' . $e->getMessage());
        }
    }

    public function destroy(SchoolAccount $schoolAccount)
    {
	    if ($schoolAccount->school_logo_url) {
	        Storage::disk('public')->delete($schoolAccount->school_logo_url);
	    }
	    
	    if ($schoolAccount->school_banner_url) {
	        Storage::disk('public')->delete($schoolAccount->school_banner_url);
	    }
	    
    	$user = $schoolAccount->user;
        $schoolAccount->delete();
        $user->delete();

        return redirect()->route('admin.index')->with('success', 'تم حذف حساب المدرسة بنجاح');
    }

    public function deleteImageById($schoolId, Request $request)
    {
        $validated = $request->validate([
            'delete_image_type' => 'required|in:logo,banner'
        ]);
        
        $schoolAccount = SchoolAccount::findOrFail($schoolId);
        
        DB::beginTransaction();
        
        try {
            if($request->delete_image_type == 'logo' && $schoolAccount->school_logo_url){
                Storage::delete($schoolAccount->school_logo_url);
                $schoolAccount->update(['school_logo_url' => null]);
            }
            
            if($request->delete_image_type == 'banner' && $schoolAccount->school_banner_url){
                Storage::delete($schoolAccount->school_banner_url);
                $schoolAccount->update(['school_banner_url' => null]);
            }
        
            DB::commit();
            return response()->json(['success' => true, 'message' => 'تم حذف الصورة بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء حذف الصورة: ' . $e->getMessage()], 500);
        }
    }
}
