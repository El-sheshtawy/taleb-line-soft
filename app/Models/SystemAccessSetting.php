<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemAccessSetting extends Model
{
    protected $fillable = [
        'sunday', 'monday', 'tuesday', 'wednesday', 
        'thursday', 'friday', 'saturday'
    ];

    protected $casts = [
        'sunday' => 'boolean',
        'monday' => 'boolean', 
        'tuesday' => 'boolean',
        'wednesday' => 'boolean',
        'thursday' => 'boolean',
        'friday' => 'boolean',
        'saturday' => 'boolean',
    ];
}