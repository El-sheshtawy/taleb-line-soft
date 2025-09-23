<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSession extends Model
{
    use HasFactory;
    
    protected $fillable = ['session_number', 'teacher_note', 'student_day_id', 'teacher_id', 'follow_up_item_id'];

    public function studentDay() {
        return $this->belongsTo(StudentDay::class);
    }

    public function followUpItem() {
        return $this->belongsTo(FollowUpItem::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
