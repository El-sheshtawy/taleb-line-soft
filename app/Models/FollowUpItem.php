<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUpItem extends Model
{
    use HasFactory;
    
    protected $fillable = ['follow_up_id', 'letter', 'meaning', 'background_color', 'text_color' , 'is_absent'];

    public function followUp()
    {
        return $this->belongsTo(FollowUp::class);
    }
    
    
    public function getItemBadgeHtml()
    {
        return '<span class="badge px-2 py-1 m-1" 
                 style="background-color: ' . $this->background_color . '; color: ' . $this->text_color . ';">
                <strong>' . $this->letter . '</strong>
            </span>';            
    }
    
}
