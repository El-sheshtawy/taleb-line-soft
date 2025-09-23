<?php

namespace App\Observers;

use App\Models\SchoolAccount;
use App\Models\User;
use App\Jobs\UpdateUserPasswords;

class SchoolAccountObserver
{
    public function updating(SchoolAccount $school): void
    {
        // Check if the students' default password has changed
        $studentPasswordChanged = $school->isDirty('students_default_password');

        // Check if the teachers' default password has changed
        $teacherPasswordChanged = $school->isDirty('teachers_default_password');

        // Run job only if either password has changed
        if ($studentPasswordChanged || $teacherPasswordChanged) {
            UpdateUserPasswords::dispatch($school, $studentPasswordChanged, $teacherPasswordChanged);
        }
    }
}
