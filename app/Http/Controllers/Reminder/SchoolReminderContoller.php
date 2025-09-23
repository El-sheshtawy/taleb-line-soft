<?php

namespace App\Http\Controllers\Reminder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolReminder;
use Illuminate\Support\Facades\Auth;

class SchoolReminderContoller extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        $school = $user->user_type == 'teacher' ? $user->profile->school : $user->profile;
        
        $reminders = SchoolReminder::where('school_id', $school->id)->orderBy('created_at', 'desc')->get();
        return view('reminders.index', compact('reminders', 'school'));
    }
    
    public function store(Request $request)
    {
       $data = $request->validate([
            'title' => 'required|string',
            'content' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        $school = $user->profile;
    
        $data['school_id'] = $school->id;
        SchoolReminder::create($data);
        return redirect()->route('reminders.index')->with('success', 'تم اضافة التنبيه بنجاح');
    }
    
    public function update(SchoolReminder $schoolReminder, Request $request)
    {
        $user = Auth::user();
        $school = $user->profile;
        
        if($schoolReminder->school_id != $school->id){
            return back()->with('error', 'لا يمكن حذف هذا التنبية');
        }
        
       $data = $request->validate([
            'title' => 'required|string',
            'content' => 'nullable|string',
        ]);
    
        $schoolReminder->update($data);
        return redirect()->route('reminders.index')->with('success', 'تم تعديل التنبيه بنجاح');
    }
    
    public function destroy(SchoolReminder $schoolReminder)
    {
        $user = Auth::user();
        $school = $user->profile;
        
        if($schoolReminder->school_id != $school->id){
            return back()->with('error', 'لا يمكن حذف هذا التنبية');
        }
        
        $schoolReminder->delete();
    
        return redirect()->route('reminders.index')->with('success', 'تم حذف التنبية بنجاح');
    }
}
