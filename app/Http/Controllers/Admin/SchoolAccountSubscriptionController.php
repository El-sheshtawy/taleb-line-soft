<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolAccountSubscription;

class SchoolAccountSubscriptionController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|integer|exists:school_accounts,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ], [], [
	        'school_id' => 'اسم المدرسة',
	        'start_date' => 'تاريخ البدء',
	        'end_date' => 'تاريخ الانتهاء',
	    ]);

         $existingSubscription = SchoolAccountSubscription::where('school_id', $validated['school_id'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                      ->orWhere(function ($query) use ($validated) {
                          $query->where('start_date', '<=', $validated['start_date'])
                                ->where('end_date', '>=', $validated['end_date']);
                      });
            })
            ->exists();
    
        if ($existingSubscription) {
            return redirect()->route('admin.index')->withErrors(['school_id' => 'هناك اشتراك نشط بالفعل لهذه المدرسة في هذه الفترة']);
        }
        
        $schoolYear = SchoolAccountSubscription::create($validated);
        	
        return redirect()->route('admin.index')->with('success', 'تم إنشاء اشتراك المدرسة بنجاح');
    }

    public function update(Request $request, $id)
    {
        $schoolAccountSubscription = SchoolAccountSubscription::findOrFail($id);
        
        $validated = $request->validate([
            'school_id' => 'sometimes|integer|exists:school_accounts,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
        ], [], [
	        'school_id' => 'اسم المدرسة',
	        'start_date' => 'تاريخ البدء',
	        'end_date' => 'تاريخ الانتهاء',
	    ]);
	    
        $schoolAccountSubscription->update($validated);
        
        return redirect()->route('admin.index')->with('success', 'تم تعديل اشتراك المدرسة بنجاح');
    }

    public function destroy($id)
    {
        $schoolAccountSubscription = SchoolAccountSubscription::findOrFail($id);
        
        $schoolAccountSubscription->delete();
        
        return redirect()->route('admin.index')->with('success', 'تم حذف اشتراك المدرسة بنجاح');
    }
}
