<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\SchoolAccount;
use Illuminate\Support\Facades\Auth;

class ClassRoomController extends Controller
{
     public function store(Request $request)
    {
        $user = Auth::user();
        $school = $user->profile;
        if($user->user_type != 'school' || !$school) return back()->with('error', 'لا يوجد مدارس في السيستم');
        
        
        $validated = $request->validate([
            'name' => 'required', 
            'grade_id' => 'required|exists:grades,id',
            'meeting_room_link' => 'nullable|url',
        ], [], [
	        'name' => 'الاسم',
	        'grade_id' => 'الصف',
	        'meeting_room_link' => 'رابط الغرفة',
	    ]);
	    
	    $validated['school_id'] = $school->id;

        $class = ClassRoom::create($validated);
	
        return redirect()->route('school.index')->with('success', 'تم إنشاء الفصل بنجاح');
    }

    public function update(Request $request, ClassRoom $class)
    {
        $user = Auth::user();
        $school = $user->profile;
        if($user->user_type != 'school' || !$school) return back()->with('error', 'لا يوجد مدارس في السيستم');
        if($class->school_id != $school->id) abor(403, "unauthorized action");
        
        $validated = $request->validate([
            'name' => 'required', 
            'grade_id' => 'required|exists:grades,id',
            'meeting_room_link' => 'nullable|url',
        ], [], [
	        'name' => 'الاسم',
	        'grade_id' => 'الصف',
	         'meeting_room_link' => 'رابط الغرفة',
	    ]);

        $class->update($validated);
        
        return redirect()->route('school.index')->with('success', 'تم تعديل بيانات الفصل بنجاح');
    }

    public function destroy(ClassRoom $class)
    {
        $user = Auth::user();
        $school = $user->profile;
        if($user->user_type != 'school' || !$school) return back()->with('error', 'لا يوجد مدارس في السيستم');
        if($class->school_id != $school->id) abor(403, "unauthorized action");
        
        $class->delete();

        return redirect()->route('school.index')->with('success', 'تم حذف الفصل بنجاح');
    }
}
