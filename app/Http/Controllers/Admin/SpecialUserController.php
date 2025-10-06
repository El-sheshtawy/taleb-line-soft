<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;
use App\Models\SchoolAccount;
use App\Models\Nationality;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SpecialUserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:مراقب,مشرف',
            'school_id' => 'required|exists:school_accounts,id',
            'name' => 'required|string|max:255',
            'passport_id' => 'required|digits:12|unique:teachers',
            'phone_number' => 'nullable|digits:8',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'subject' => 'nullable|string|max:255',
            'nationality_id' => 'required|exists:nationalities,id',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'user_type' => $request->user_type,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'defualt_password' => Hash::make($request->password),
                'plain_password' => $request->password,
                'school_id' => $request->school_id
            ]);

            Teacher::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'subject' => $request->subject ?? 'عام',
                'passport_id' => $request->passport_id,
                'head_of_department' => false,
                'supervisor' => false,
                'school_id' => $request->school_id,
                'user_id' => $user->id,
                'nationality_id' => $request->nationality_id,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'تم إضافة المستخدم بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة المستخدم: ' . $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        $profile = Teacher::where('user_id', $user->id)->first();
        $profileId = $profile ? $profile->id : 0;
        
        $request->validate([
            'user_type' => 'required|in:مراقب,مشرف',
            'school_id' => 'required|exists:school_accounts,id',
            'name' => 'required|string|max:255',
            'passport_id' => 'required|digits:12|unique:teachers,passport_id,' . $profileId,
            'phone_number' => 'nullable|digits:8',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'subject' => 'nullable|string|max:255',
            'nationality_id' => 'required|exists:nationalities,id',
        ]);

        DB::beginTransaction();

        try {
            $updateData = [
                'user_type' => $request->user_type,
                'username' => $request->username,
                'school_id' => $request->school_id,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
                $updateData['defualt_password'] = Hash::make($request->password);
                $updateData['plain_password'] = $request->password;
            }

            \Log::info('Updating user with data: ', $updateData);
            try {
                \Log::info('Current user data before update: ', $user->toArray());
                $userUpdated = $user->update($updateData);
                \Log::info('User update result: ' . ($userUpdated ? 'SUCCESS' : 'FAILED'));
                $freshUser = $user->fresh();
                if ($freshUser) {
                    \Log::info('User data after update: ', $freshUser->toArray());
                } else {
                    \Log::info('User not found after update');
                }
            } catch (\Exception $e) {
                \Log::error('User update failed with error: ' . $e->getMessage());
                throw $e;
            }
            
            // Update profile
            if ($profile) {
                $profileData = [
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'subject' => $request->subject ?? 'عام',
                    'passport_id' => $request->passport_id,
                    'school_id' => $request->school_id,
                    'nationality_id' => $request->nationality_id,
                ];
                \Log::info('Updating profile with data: ', $profileData);
                $profileUpdated = $profile->update($profileData);
                \Log::info('Profile update result: ' . ($profileUpdated ? 'SUCCESS' : 'FAILED'));
            } else {
                \Log::error('No profile found for user: ' . $user->id);
            }
            
            // Update school account with viewer/supervisor info
            if ($request->school_id) {
                $school = SchoolAccount::find($request->school_id);
                if ($school) {
                    if ($request->user_type === 'مراقب') {
                        $school->update([
                            'viewer_name' => $request->name,
                            'viewer_password' => $request->filled('password') ? $request->password : $school->viewer_password
                        ]);
                    } elseif ($request->user_type === 'مشرف') {
                        $school->update([
                            'supervisor_name' => $request->name,
                            'supervisor_password' => $request->filled('password') ? $request->password : $school->supervisor_password
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'تم تحديث المستخدم بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المستخدم: ' . $e->getMessage());
        }
    }
    // ok

    public function destroy($id)
    {
        \Log::info('=== STARTING USER DELETION ===');
        \Log::info('User ID from route: ' . $id);
        
        // Find the user by ID
        $user = User::find($id);
        if (!$user) {
            \Log::error('User not found with ID: ' . $id);
            return redirect()->back()->with('error', 'المستخدم غير موجود');
        }
        
        \Log::info('User Type: ' . $user->user_type);
        \Log::info('Username: ' . $user->username);
        
        DB::beginTransaction();

        try {
            
            // Handle foreign key constraints for special users
            if (in_array($user->user_type, ['مراقب', 'مشرف'])) {
                $teacher = Teacher::where('user_id', $user->id)->first();
                \Log::info('Teacher found: ' . ($teacher ? 'YES (ID: ' . $teacher->id . ')' : 'NO'));
                
                if ($teacher) {
                    // Check student sessions before update
                    $sessionsCount = DB::table('student_sessions')->where('teacher_id', $teacher->id)->count();
                    \Log::info('Student sessions found: ' . $sessionsCount);
                    
                    // Set teacher_id to NULL in student_sessions
                    $updated = DB::table('student_sessions')->where('teacher_id', $teacher->id)->update(['teacher_id' => null]);
                    \Log::info('Student sessions updated: ' . $updated);
                    
                    // Delete teacher profile
                    $teacherDeleted = $teacher->delete();
                    \Log::info('Teacher deleted: ' . ($teacherDeleted ? 'YES' : 'NO'));
                }
            }
            
            // Update school account to remove viewer/supervisor info
            if ($user->school_id) {
                $school = SchoolAccount::find($user->school_id);
                if ($school) {
                    if ($user->user_type === 'مراقب') {
                        $school->update([
                            'viewer_name' => null,
                            'viewer_password' => null
                        ]);
                    } elseif ($user->user_type === 'مشرف') {
                        $school->update([
                            'supervisor_name' => null,
                            'supervisor_password' => null
                        ]);
                    }
                }
            }
            
            // Delete the user
            \Log::info('Attempting to delete user...');
            $userDeleted = $user->delete();
            \Log::info('User delete() returned: ' . ($userDeleted ? 'TRUE' : 'FALSE'));
            
            // Check if user still exists after deletion
            $userStillExists = User::find($user->id);
            \Log::info('User still exists after deletion: ' . ($userStillExists ? 'YES' : 'NO'));
            
            // Check total user count
            $totalUsers = User::whereIn('user_type', ['مراقب', 'مشرف'])->count();
            \Log::info('Total special users remaining: ' . $totalUsers);

            DB::commit();
            \Log::info('=== TRANSACTION COMMITTED ===');
            return redirect()->back()->with('success', 'تم حذف المستخدم بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('=== DELETION FAILED ===');
            \Log::error('Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المستخدم: ' . $e->getMessage());
        }
    }
}