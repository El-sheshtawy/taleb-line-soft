<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email', 
        'passport_id', 
        'gender', 
        'phone_number', 
        'phone_number_2', 
        'note', 
        
        'grade_id', 
        'class_id', 
        'school_id',
        'user_id',
        'nationality_id',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function school(){
        return $this->belongsTo(SchoolAccount::class);
    }
    
    public function grade(){
        return $this->belongsTo(Grade::class);
    }
    
    public function classRoom(){
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
    
    public function nationality(){
        return $this->belongsTo(Nationality::class);
    }
    
    public function days(){
        return $this->hasMany(StudentDay::class);
    }
}
