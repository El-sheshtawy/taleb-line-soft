<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
        ], [], [
	        'school_name' => 'الاسم',
	        'password' => 'كلمة المرور',
	        'username' => 'اسم المستخدم',
	    ]);
	    
	    DB::beginTransaction();
    	    
    	try{
    	    $validated['username'] = preg_replace('/[^A-Za-z0-9]/', '', trim($validated['username']));
    
            $user = User::create([
                'user_type' => 'admin',
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'defualt_password' => $validated['password']
            ]);
            
            $admin = Admin::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'password' => $validated['password'],
                'user_id' => $user->id,
            ]);
    	
             DB::commit();
            return redirect()->route('admin.index')->with('success', 'تم إنشاء حساب المشرف بنجاح');
    	} catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('admin.index')->with('error', 'حدث خطأ أثناء إضافة المشرف: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,' . $admin->id,
            'password' => 'nullable|string|min:6',
        ], [], [
	        'school_name' => 'الاسم',
	        'password' => 'كلمة المرور',
	        'username' => 'اسم المستخدم',
	    ]);
	    
	       
	    DB::beginTransaction();
	    
    	try{
    	    $validated['username'] = preg_replace('/[^A-Za-z0-9]/', '', trim($validated['username']));
    	    
    	    if ($validated['username'] !== $admin->username || $validated['password'] !== $admin->password) {
                $admin->user->update([
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'defualt_password' => $request->password
                ]);
	        }
    	    
            $admin->update($validated);
            
    	    DB::commit();
            return redirect()->route('admin.index')->with('success', 'تم تعديل بيانات حساب المشرف بنجاح');
    	} catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('admin.index')->with('error', 'حدث خطأ أثناء تعديل بيانات المشرف: ' . $e->getMessage());
        }
    }

    public function destroy(Admin $admin)
    {
    	$user = $admin->user;
        $admin->delete();
        $user->delete();

        return redirect()->route('admin.index')->with('success', 'تم حذف حساب المشرف بنجاح');
    }
}
