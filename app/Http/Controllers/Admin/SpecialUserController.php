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
        $request->validate([
            'user_type' => 'required|in:مراقب,مشرف',
            'school_id' => 'required|exists:school_accounts,id',
            'name' => 'required|string|max:255',
            'passport_id' => 'required|digits:12|unique:teachers,passport_id,' . $user->profile->id,
            'phone_number' => 'nullable|digits:8',
            'username' => 'required|string|unique:users,username,' . $user->id,
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
            }

            $user->update($updateData);

            $user->profile->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'subject' => $request->subject ?? 'عام',
                'passport_id' => $request->passport_id,
                'school_id' => $request->school_id,
                'nationality_id' => $request->nationality_id,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'تم تحديث المستخدم بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المستخدم: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();

        try {
            \Log::info('Attempting to delete user: ' . $user->id . ' - ' . $user->username);
            
            // Delete associated teacher profile if exists
            if (in_array($user->user_type, ['مراقب', 'مشرف'])) {
                $teacher = Teacher::where('user_id', $user->id)->first();
                if ($teacher) {
                    \Log::info('Deleting teacher profile: ' . $teacher->id);
                    $teacher->delete();
                }
            }
            
            // Delete the user
            \Log::info('Deleting user: ' . $user->id);
            $deleted = $user->delete();
            \Log::info('User deletion result: ' . ($deleted ? 'success' : 'failed'));

            DB::commit();
            return redirect()->back()->with('success', 'تم حذف المستخدم بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المستخدم: ' . $e->getMessage());
        }
    }
}