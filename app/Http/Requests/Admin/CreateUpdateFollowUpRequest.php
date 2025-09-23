<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateFollowUpRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'notes' => 'nullable|string',
            'follow_up_items' => 'required|array',
            'follow_up_items.*.letter' => 'required|string|max:5',
            'follow_up_items.*.meaning' => 'nullable|string|max:255',
            'follow_up_items.*.text_color' => 'nullable|string|max:7', // HEX color
            'follow_up_items.*.background_color' => 'nullable|string|max:7', // HEX color
            'follow_up_items.*.is_absent' => 'nullable|in:on,off',
        ];
    }
    
    public function attributes()
    {
        return [
            'follow_up_items' => 'عناصر المتابعة ',
            'follow_up_items.*.letter' => 'رمز العنصر ',
            'follow_up_items.*.meaning' => 'معني العنصر',
            'follow_up_items.*.text_color' => 'لون نص العنصر',
            'follow_up_items.*.background_color' => 'لون خلفية العنصر',
            'follow_up_items.*.is_absent' => 'حالة غياب',

            'name' => 'الاسم',
            'notes' => 'الملاحظات',
        ];
    }
}
