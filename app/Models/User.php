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
        'defualt_password',
        'plain_password',
        'school_id'
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
        switch ($this->user_type) {
            case 'admin':
                return $this->hasOne(Admin::class, 'user_id');
            case 'student':
                return $this->hasOne(Student::class, 'user_id');
            case 'school':
                return $this->hasOne(SchoolAccount::class, 'user_id');
            case 'teacher':
                return $this->hasOne(Teacher::class, 'user_id');
            case 'مراقب':
            case 'مشرف':
                return null; // No profile for viewers/supervisors
            default:
                return null;
        }
    }
    
    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
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
    
    public function getSchool()
    {
        if (in_array($this->user_type, ['مراقب', 'مشرف']) && $this->school_id) {
            return SchoolAccount::find($this->school_id);
        } elseif ($this->user_type === 'teacher' && $this->profile) {
            return $this->profile->school;
        } elseif ($this->user_type === 'school') {
            return $this->profile;
        }
        
        return null;
    }
}
