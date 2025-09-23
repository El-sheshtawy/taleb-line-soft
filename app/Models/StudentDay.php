<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDay extends Model
{
    use HasFactory;
    
    protected $fillable = ['date', 'is_absent', 'school_id', 'student_id'];

    public function sessions() {
        return $this->hasMany(StudentSession::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
    
    public function school() {
        return $this->belongsTo(School::class);
    }
}
