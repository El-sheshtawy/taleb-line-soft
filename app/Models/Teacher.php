<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    
     
    protected $fillable = [
        'name',
        'passport_id',
        'phone_number',  
        'subject', 
        
        'head_of_department',
        'supervisor',
        
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
    
    public function nationality(){
        return $this->belongsTo(Nationality::class);
    }
}
