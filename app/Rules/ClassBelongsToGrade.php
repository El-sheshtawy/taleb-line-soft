<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ClassBelongsToGrade implements Rule
{
    protected $gradeId, $schoolId;

    public function __construct($gradeId, $schoolId)
    {
        $this->gradeId = $gradeId;
        $this->schoolId = $schoolId;
    }

    public function passes($attribute, $value)
    {
        return DB::table('classes')
            ->where('id', $value)
            ->where('school_id', $this->schoolId)
            ->where('grade_id', $this->gradeId)
            ->exists();
    }

    public function message()
    {
        return 'الفصل المحدد لا ينتمي إلى الصف المختار أو المدرسة.';
    }
}