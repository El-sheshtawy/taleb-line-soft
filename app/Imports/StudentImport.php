<?php
namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use App\Models\SchoolAccount;
use App\Models\ClassRoom;
use App\Models\Grade;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\DB;

class StudentImport implements ToModel, WithValidation, WithHeadingRow, SkipsEmptyRows
{
    private $headerMap = [
        'الصف' => 'grade',
        'الفصل' => 'class',
        'الاسم' => 'name',
        'الجنس' => 'gender',
        'الجنسية' => 'nationality_id',
        'الرقم المدني' => 'passport_id',
        'الهاتف' => 'phone_number',
        'الهاتف الثاني' => 'phone_number_2',
        'ملاحظات' => 'note'
    ];

    private $school;
    private $grades;
    private $classes;

    public function __construct()
    {
    	set_time_limit(300);
        HeadingRowFormatter::default('none');
        
        $user = Auth::user();
        $school = $user->profile;
        if($user->user_type != 'school' || !$school) {
            throw new \Exception('المدرسة غير موجودة');
        }
        $this->school = $school;
        
        $this->grades = Grade::pluck('id', 'name');
        $this->classes = ClassRoom::where('school_id', $this->school->id)
            ->get()
            ->groupBy(['grade_id', 'name']);
    }

    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {
            $gradeId = $this->grades[$row['grade']] ?? null;
            if (!$gradeId) {
                throw new \Exception("الصف المحدد ({$row['grade']}) لا ينتمي إلى في المدرسة ({$this->school->id}) في السطر " . ($this->getRowNumber() + 1));
            }
            
            $classId = $this->classes[$gradeId][$row['class']][0]->id ?? null;
            if (!$classId) {
                throw new \Exception("الفصل المحدد ({$row['class']}) لا ينتمي إلى الصف ({$row['grade']}) في المدرسة ({$this->school->id}) في السطر " . ($this->getRowNumber() + 1));
            }
    
            $user = User::create([
                'user_type' => 'student',
                'username' => $row['passport_id'],
                'password' => Hash::make($row['phone_number'] ?? $this->school->students_default_password ?? '123456789'),
                'defualt_password' => Hash::make($this->school->students_default_password ?? '123456789')
            ]);
    
            return new Student([
                'name' => $row['name'],
                'passport_id' => $row['passport_id'],
                'gender' => $row['gender'] == "0" ? false : true,
                'phone_number' => $row['phone_number'] ?? null,
                'phone_number_2' => $row['phone_number_2'] ?? null,
                'note' => $row['note'] ?? null,
                'grade_id' => $gradeId,
                'class_id' => $classId,
                'school_id' => $this->school->id,
                'user_id' => $user->id,
                'nationality_id' => $row['nationality_id'],
            ]);
        });
    }
    
    public function rules(): array
    {
        return [
            'grade' => 'required|exists:grades,name',
            'class' => 'required|exists:classes,name',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:0,1',
            'nationality_id' => 'required|exists:nationalities,id',
            'passport_id' => 'required|digits:12|unique:students,passport_id',
            'phone_number' => 'nullable|digits:8',
            'phone_number_2' => 'nullable|digits:8',
        ];
    }
    
    public function customValidationAttributes()
    {
        return [
            'name' => 'الاسم',
            'passport_id' => 'الرقم المدني',
            'gender' => 'الجنس',
            'phone_number' => 'الهاتف',
            'phone_number_2' => 'الهاتف الثاني',
            'class' => 'الفصل',
            'grade' => 'الصف',
            'nationality_id' => 'الجنسية',
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