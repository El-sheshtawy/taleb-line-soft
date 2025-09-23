<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    protected $table = 'follow_ups';

    protected $fillable = ['name', 'notes'];

    public function items()
    {
        return $this->hasMany(FollowUpItem::class);
    }    
    
    public function getItemsBadgesHtml()
    {
        return $this->items->map(function ($item) {
            return '<span class="badge px-2 py-1" 
                         style="margin: 0 1px;background-color: ' . $item->background_color . '; color: ' . $item->text_color . ';">
                        <strong>' . $item->letter . '</strong>
                    </span>';
        })->implode(' ');
    }
    
    public function getAbsentItemBadgeHtml()
    {
        $absent = $this->items->where('is_absent', true)->first();
        if(!$absent){
            $absent = $this->items->first();
        }
        
        return '<span class="badge px-2 py-1 m-1" 
                 style="background-color: ' . $absent->background_color . '; color: ' . $absent->text_color . ';">
                <strong>' . $absent->letter . '</strong>
            </span>';            
    }
    
    public function getAbsent()
    {
        $absent = $this->items->where('is_absent', true)->first();
        if(!$absent){
            $this->items->first();
        }
        
        return $absent;
    }

    
}
