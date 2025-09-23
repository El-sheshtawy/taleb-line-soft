<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolAccountSubscription extends Model
{
    use HasFactory;
    
    protected $fillable = ['school_id', 'start_date', 'end_date'];

    public function schoolAccount()
    {
        return $this->belongsTo(SchoolAccount::class, 'school_id');
    }
}
