<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use App\Models\SchoolAccount;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class TeachersImport implements ToModel, WithValidation, WithHeadingRow, SkipsEmptyRows
{
    private $headerMap = [
        'الاسم' => 'name',
        'الجنسية' => 'nationality_id',
        'الرقم المدني' => 'passport_id',
        'الهاتف' => 'phone_number',
        'المادة' => 'subject'
    ];

    private $school;

    public function __construct()
    {
        HeadingRowFormatter::default('none');
        
        $user = Auth::user();
        $school = $user->profile;
        if($user->user_type != 'school' || !$school) {
            throw new \Exception('المدرسة غير موجودة');
        }
        $this->school = $school;
    }
    
    public function model(array $row)
    {
        $user = User::create([
            'user_type' => 'teacher',
            'username' => $row['passport_id'],
            'password' => Hash::make($row['phone_number'] ?? $this->school->teachers_default_password ?? '123456789'),
            'defualt_password' => Hash::make($this->school->teachers_default_password ?? '123456789')
        ]);

        return new Teacher([
            'name' => $row['name'],
            'passport_id' => $row['passport_id'],
            'phone_number' => $row['phone_number'] ?? null,
            'subject' => $row['subject'],
            'school_id' => $this->school->id,
            'user_id' => $user->id,
            'nationality_id' => $row['nationality_id']
        ]);
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'passport_id' => 'required|digits:12|unique:teachers,passport_id',
            'phone_number' => 'nullable|digits:8',
            'subject' => 'required|string|max:255',
            'nationality_id' => 'required|exists:nationalities,id',
        ];
    }
    
    public function customValidationAttributes()
    {
        return [
            'name' => 'الاسم',
            'phone_number' => 'الهاتف',
            'subject' => 'المادة',
            'nationality_id' => 'الجنسية',
            'passport_id' => 'الرقم المدني'
        ];
    }
    
    private function getRowNumber()
    {
        return $this->rowNumber ?? 0;
    }

    public function onRow($row)
    {
        $this->rowNumber = $row;
    }

    public function prepareForValidation($data, $index)
    {
        // Normalize Arabic headers first
        $normalizedData = [];
        foreach ($data as $key => $value) {
            $normalizedKey = trim(preg_replace('/[^\p{Arabic}\s]/u', '', $key));
            $normalizedData[$normalizedKey] = $value;
        }

        // Then map to English keys
        $mappedData = [];
        foreach ($this->headerMap as $arabic => $english) {
            $normalizedArabic = trim(preg_replace('/[^\p{Arabic}\s]/u', '', $arabic));
            if (isset($normalizedData[$normalizedArabic])) {
                $mappedData[$english] = $normalizedData[$normalizedArabic];
            }
        }
        
        return $mappedData;
    }
}
