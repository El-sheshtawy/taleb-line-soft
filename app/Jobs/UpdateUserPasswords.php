<?php

namespace App\Jobs;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $school;
    protected $updateStudents;
    protected $updateTeachers;

    public function __construct($school, $updateStudents, $updateTeachers)
    {
        $this->school = $school;
        $this->updateStudents = $updateStudents;
        $this->updateTeachers = $updateTeachers;
    }

    public function handle(): void
    {
        // Update Student Passwords if changed
        if ($this->updateStudents) {
            User::whereIn('id', Student::where('school_id', $this->school->id)->pluck('user_id'))
                ->where('user_type', 'student')
                ->update(['defualt_password' => Hash::make($this->school->students_default_password)]);
        }

        // Update Teacher Passwords if changed
        if ($this->updateTeachers) {
            User::whereIn('id', Teacher::where('school_id', $this->school->id)->pluck('user_id'))
                ->where('user_type', 'teacher')
                ->update(['defualt_password' => Hash::make($this->school->teachers_default_password)]);
        }
    }
}
