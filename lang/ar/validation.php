<?php

return [

    /*
    |--------------------------------------------------------------------------
    | رسائل التحقق الافتراضية في Laravel
    |--------------------------------------------------------------------------
    |
    | تحتوي السطور التالية على رسائل الخطأ الافتراضية المستخدمة بواسطة
    | كائن التحقق. يمكنك تعديل كل رسالة وفقًا لاحتياجاتك.
    |
    */

    'accepted' => 'يجب قبول :attribute.',
    'accepted_if' => 'يجب قبول :attribute عندما يكون :other بقيمة :value.',
    'active_url' => 'الحقل :attribute لا يُمثّل رابطًا صحيحًا.',
    'after' => 'يجب أن يكون الحقل :attribute تاريخًا بعد :date.',
    'after_or_equal' => 'يجب أن يكون الحقل :attribute تاريخًا بعد أو يساوي :date.',
    'alpha' => 'يجب أن يحتوي الحقل :attribute على حروف فقط.',
    'alpha_dash' => 'يجب أن يحتوي الحقل :attribute على حروف، أرقام، شرطات وشرطات سفلية فقط.',
    'alpha_num' => 'يجب أن يحتوي الحقل :attribute على حروف وأرقام فقط.',
    'array' => 'يجب أن يكون الحقل :attribute مصفوفة.',
    'before' => 'يجب أن يكون الحقل :attribute تاريخًا قبل :date.',
    'before_or_equal' => 'يجب أن يكون الحقل :attribute تاريخًا قبل أو يساوي :date.',
    'between' => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file' => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'string' => 'يجب أن يكون عدد حروف :attribute بين :min و :max.',
        'array' => 'يجب أن يحتوي الحقل :attribute على بين :min و :max عنصرًا.',
    ],
    'boolean' => 'يجب أن يكون الحقل :attribute صحيحًا أو خطأً.',
    'confirmed' => 'تأكيد الحقل :attribute غير متطابق.',
    'date' => 'الحقل :attribute ليس تاريخًا صحيحًا.',
    'date_equals' => 'يجب أن يكون الحقل :attribute مطابقًا للتاريخ :date.',
    'date_format' => 'يجب أن يكون الحقل :attribute بالتنسيق :format.',
    'different' => 'يجب أن يكون الحقل :attribute مختلفًا عن :other.',
    'digits' => 'يجب أن يحتوي الحقل :attribute على :digits أرقام.',
    'digits_between' => 'يجب أن يكون الحقل :attribute بين :min و :max رقمًا.',
    'dimensions' => 'أبعاد صورة :attribute غير صحيحة.',
    'distinct' => 'الحقل :attribute يحتوي على قيمة مكررة.',
    'email' => 'يجب أن يكون الحقل :attribute بريدًا إلكترونيًا صحيحًا.',
    'ends_with' => 'يجب أن ينتهي الحقل :attribute بأحد القيم التالية: :values.',
    'exists' => 'الحقل :attribute غير صالح.',
    'file' => 'يجب أن يكون الحقل :attribute ملفًا.',
    'filled' => 'يجب أن يحتوي الحقل :attribute على قيمة.',
    'gt' => [
        'numeric' => 'يجب أن يكون الحقل :attribute أكبر من :value.',
        'file' => 'يجب أن يكون حجم الملف :attribute أكبر من :value كيلوبايت.',
        'string' => 'يجب أن يحتوي الحقل :attribute على أكثر من :value حروف.',
        'array' => 'يجب أن يحتوي الحقل :attribute على أكثر من :value عنصرًا.',
    ],
    'gte' => [
        'numeric' => 'يجب أن يكون الحقل :attribute أكبر من أو يساوي :value.',
        'file' => 'يجب أن يكون حجم الملف :attribute أكبر من أو يساوي :value كيلوبايت.',
        'string' => 'يجب أن يحتوي الحقل :attribute على :value حروف أو أكثر.',
        'array' => 'يجب أن يحتوي الحقل :attribute على :value عنصر أو أكثر.',
    ],
    'image' => 'يجب أن يكون الحقل :attribute صورة.',
    'in' => 'الحقل :attribute غير صالح.',
    'in_array' => 'الحقل :attribute غير موجود في :other.',
    'integer' => 'يجب أن يكون الحقل :attribute عددًا صحيحًا.',
    'ip' => 'يجب أن يكون الحقل :attribute عنوان IP صحيحًا.',
    'ipv4' => 'يجب أن يكون الحقل :attribute عنوان IPv4 صحيحًا.',
    'ipv6' => 'يجب أن يكون الحقل :attribute عنوان IPv6 صحيحًا.',
    'json' => 'يجب أن يكون الحقل :attribute نصًا من نوع JSON.',
    'lt' => [
        'numeric' => 'يجب أن يكون الحقل :attribute أقل من :value.',
        'file' => 'يجب أن يكون حجم الملف :attribute أقل من :value كيلوبايت.',
        'string' => 'يجب أن يحتوي الحقل :attribute على أقل من :value حروف.',
        'array' => 'يجب أن يحتوي الحقل :attribute على أقل من :value عنصرًا.',
    ],
    'lte' => [
        'numeric' => 'يجب أن يكون الحقل :attribute أقل من أو يساوي :value.',
        'file' => 'يجب أن يكون حجم الملف :attribute أقل من أو يساوي :value كيلوبايت.',
        'string' => 'يجب أن يحتوي الحقل :attribute على :value حروف أو أقل.',
        'array' => 'يجب ألا يحتوي الحقل :attribute على أكثر من :value عنصرًا.',
    ],
    'max' => [
        'numeric' => 'يجب ألا تكون قيمة الحقل :attribute أكبر من :max.',
        'file' => 'يجب ألا يتجاوز حجم الملف :attribute :max كيلوبايت.',
        'string' => 'يجب ألا يتجاوز عدد الحروف في الحقل :attribute :max.',
        'array' => 'يجب ألا يحتوي الحقل :attribute على أكثر من :max عنصرًا.',
    ],
    'mimes' => 'يجب أن يكون الحقل :attribute ملفًا من نوع: :values.',
    'mimetypes' => 'يجب أن يكون الحقل :attribute من نوع: :values.',
    'min' => [
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute على الأقل :min.',
        'file' => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت.',
        'string' => 'يجب ألا يقل عدد الحروف في الحقل :attribute عن :min.',
        'array' => 'يجب أن يحتوي الحقل :attribute على الأقل على :min عناصر.',
    ],
    'not_in' => 'الحقل :attribute غير صالح.',
    'not_regex' => 'تنسيق الحقل :attribute غير صالح.',
    'numeric' => 'يجب أن يكون الحقل :attribute رقمًا.',
    'required' => 'الحقل :attribute مطلوب.',
    'same' => 'يجب أن يطابق الحقل :attribute الحقل :other.',
    'size' => [
        'numeric' => 'يجب أن تكون قيمة الحقل :attribute :size.',
        'file' => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت.',
        'string' => 'يجب أن يحتوي الحقل :attribute على :size حروف.',
        'array' => 'يجب أن يحتوي الحقل :attribute على :size عنصرًا.',
    ],
    'unique' => 'قيمة الحقل :attribute مستخدمة من قبل.',

];
