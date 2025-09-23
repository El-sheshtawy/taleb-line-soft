<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;

class AcademicYearController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ], [], [
	        'name' => 'الاسم',
	        'start_date' => 'وقت البدء',
	        'end_date' => 'وقت الانتهاء',
	        'status' => 'الحالة',
	    ]);
	    
	    $overlappingYear = AcademicYear::where(function ($query) use ($validated) {
            $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']]);
        })->exists();

        if ($overlappingYear) {
            return redirect()->back()->withErrors(['start_date' => 'توجد فترة دراسية تتداخل مع التواريخ المحددة.']);
        }
	    
	    if ($validated['status'] === 'active') {
            AcademicYear::where('status', 'active')->update(['status' => 'inactive']);
        }
        
        

        $academicYear = AcademicYear::create($validated);
	
        return redirect()->route('admin.index')->with('success', 'تم إنشاء العام الدراسي بنجاح');
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ], [], [
	        'name' => 'الاسم',
	        'start_date' => 'وقت البدء',
	        'end_date' => 'وقت الانتهاء',
	        'status' => 'الحالة ',
	    ]);
	    $activatedAcademicYear = AcademicYear::where('status', 'active')->where('id', '!=', $academicYear->id)->first();
	    if ($validated['status'] === 'active' && $activatedAcademicYear) {
	        $activatedAcademicYear->update(['status' => 'inactive']);
        }

        $overlappingYear = AcademicYear::where('id', '!=', $academicYear->id)
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']]);
            })->exists();

        if ($overlappingYear) {
            return redirect()->back()->withErrors(['start_date' => 'توجد فترة دراسية تتداخل مع التواريخ المحددة.']);
        }


        $academicYear->update($validated);
        
        return redirect()->route('admin.index')->with('success', 'تم تعديل بيانات  العام الدراسي بنجاح');
    }

    public function destroy(Request $request, AcademicYear $academicYear)
    {
        if ($request->has('delete_related')) {
            $academicYear->classes()->delete();
            $academicYear->students()->delete();
            $academicYear->teachers()->delete();
        } 
        $academicYear->delete();

        return redirect()->route('admin.index')->with('success', 'تم حذف العام الدراسي  بنجاح');
    }
}
