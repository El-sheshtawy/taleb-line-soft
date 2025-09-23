<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'user_type',
        'username',
        'password',
        'defualt_password'
    ];
    
    public const VIEWER_ROLE = 'مراقب';
    public const SUPERVISOR_ROLE = 'مشرف';

    protected $hidden = [
        'password',
        'defualt_password',
        'remember_token',
    ];
    
    protected $casts = ['created_at' => 'datetime'];
    
    public function profile()
    {
        if ($this->user_type === 'admin') {
            return $this->hasOne(Admin::class, 'user_id');
        }elseif ($this->user_type === 'student') {
            return $this->hasOne(Student::class, 'user_id');
        } elseif ($this->user_type === 'school') {
            return $this->hasOne(SchoolAccount::class, 'user_id');
        } elseif ($this->user_type === 'teacher') {
            return $this->hasOne(Teacher::class, 'user_id');
        } elseif ($this->user_type === 'مراقب') {
            return $this->hasOne(Teacher::class, 'user_id');
        } elseif ($this->user_type === 'مشرف') {
            return $this->hasOne(Teacher::class, 'user_id');
        }
        
        return null;
    }
    
    public function canPerformActions($currentRoute = null)
    {
        if ($this->user_type === 'مراقب') {
            return false;
        }
        
        if ($this->user_type === 'مشرف') {
            return $currentRoute && str_contains($currentRoute, 'teacher.');
        }
        
        return true;
    }
}
