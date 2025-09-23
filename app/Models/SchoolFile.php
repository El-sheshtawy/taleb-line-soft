<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolFile extends Model
{
    use HasFactory;
    
    protected $fillable = ['school_id', 'name', 'path', 'show', 'type', 'note'];
    
    public function schoolAccount()
    {
        return $this->belongsTo(School::class);
    }
}
