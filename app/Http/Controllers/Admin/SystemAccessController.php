<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemAccessSetting;
use Illuminate\Http\Request;

class SystemAccessController extends Controller
{
    public function update(Request $request)
    {
        $settings = SystemAccessSetting::firstOrNew();
        
        $settings->sunday = $request->has('sunday');
        $settings->monday = $request->has('monday');
        $settings->tuesday = $request->has('tuesday');
        $settings->wednesday = $request->has('wednesday');
        $settings->thursday = $request->has('thursday');
        $settings->friday = $request->has('friday');
        $settings->saturday = $request->has('saturday');
        
        $settings->save();
        
        return response()->json([
            'success' => true,
            'message' => 'تم حفظ إعدادات الوصول بنجاح'
        ]);
    }
}