<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;
    
    protected $table = 'classes';
    
    protected $fillable = ['name','meeting_room_link','grade_id', 'school_id'];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    
    public function school(){
        return $this->belongsTo(SchoolAccount::class, 'school_id');
    }
    
    
    // me
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}
