<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FollowUp;
use App\Models\FollowUpItem;
use App\Http\Requests\Admin\CreateUpdateFollowUpRequest;
use Illuminate\Support\Facades\DB;

class FollowUpController extends Controller
{
    public function store(CreateUpdateFollowUpRequest $request)
    {

        $followUp = FollowUp::create(['name' => $request->name, 'notes' => $request->notes]);
        
        foreach ($request->follow_up_items as $item) {
            FollowUpItem::create([
                'follow_up_id' => $followUp->id,
                'letter' => $item['letter'],
                'meaning' => $item['meaning'] ?? $item['letter'],
                'text_color' => $item['text_color'] ?? '#ffffff',
                'background_color' => $item['background_color'] ?? '#0000FF',
                'is_absent' => isset($item['is_absent']) && $item['is_absent'] != null ? 1 : 0,
            ]);
        }
	
        return redirect()->route('admin.index')->with('success', 'تم إنشاء نظام متابعه بنجاح');
    }

    public function update(CreateUpdateFollowUpRequest $request, FollowUp $followUp)
    {

         try {
            DB::beginTransaction();
    
            $followUp->update(['name' => $request->name ?? null, 'notes' => $request->notes ?? null]);
    
            $followUp->items()->delete();
    
            foreach ($request->follow_up_items as $item) {
                FollowUpItem::create([
                    'follow_up_id' => $followUp->id,
                    'letter' => $item['letter'],
                    'meaning' => $item['meaning'] ?? $item['letter'],
                    'text_color' => $item['text_color'] ?? '#ffffff',
                    'background_color' => $item['background_color'] ?? '#0000FF',
                    'is_absent' => isset($item['is_absent']) && $item['is_absent'] != null ? 1 : 0,
                ]);
            }
            
            DB::commit();
            return redirect()->route('admin.index')->with('success', 'تم تعديل بيانات نظام المتابعة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث نظام المتابعة ' . $e->getMessage());
        }
        
    }

    public function destroy(FollowUp $followUp)
    {
        $followUp->delete();

        return redirect()->route('admin.index')->with('success', 'تم حذف نظام المتابعة بنجاح');
    }
}
