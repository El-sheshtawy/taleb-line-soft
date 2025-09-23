<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolReminder extends Model
{
    use HasFactory;
    
    protected $fillable = ['school_id', 'title', 'content'];

    public function school()
    {
        return $this->belongsTo(SchoolAccount::class);
    }
}
