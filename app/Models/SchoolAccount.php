<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'school_logo_url',
        'school_banner_url',
        'username',
        'password',
        'subscription_state',
        'edu_region',
        'follow_up_id',
        'teachers_default_password',
        'students_default_password',
        'user_id',
        'level_id',
        'absence_count',
        'table_general',
        'table_classes',
        'viewer_name',
        'viewer_password',
        'supervisor_name',
        'supervisor_password',
    ];

    public function getSchoolLogoUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function getSchoolBannerUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
    public function getTableGeneralAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
    public function getTableClassesAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function followUp()
    {
        return $this->belongsTo(FollowUp::class, 'follow_up_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function subscription()
    {
        return $this->hasOne(SchoolAccountSubscription::class, 'school_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'school_id');
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'school_id')->orderBy('name', 'asc');
    }

    public function classes()
    {
        return $this->hasMany(ClassRoom::class, 'school_id');
    }

    public function files()
    {
        return $this->hasMany(SchoolFile::class, 'school_id');
    }

    public function reminders()
    {
        return $this->hasMany(SchoolReminder::class, 'school_id');
    }
}
