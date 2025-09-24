<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function login()
    {
        return view('login');
    }
    
    public function loginAction(LoginRequest $request)
    {
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['username' => 'بيانات الاعتماد غير صحيحة']);
        }

        if (Hash::check($request->password, $user->password) || Hash::check($request->password, $user->defualt_password)) {
            
            Auth::login($user, $request->remember ?? false);
            
            switch ($user->user_type) {
                case 'admin':
                    return redirect()->route('admin.index');
                case 'school':
                    return redirect()->route('school.index');
                case 'teacher':
                    return redirect()->route('teacher.index');
                case 'student':
                    return redirect()->route('admin.index');
                case 'father':
                    return redirect()->route('father.index');
                case 'مراقب':
                case 'مشرف':
                    return redirect()->route('school.index');
                default:
                    return redirect()->route('home');
            }
        }

        return back()->withErrors(['password' => 'كلمة المرور غير صحيحة']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->to('login');
    }
}
