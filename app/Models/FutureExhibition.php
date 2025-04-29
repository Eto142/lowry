<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FutureExhibition extends Model
{
    protected $table = 'future_exhibitions';

    protected $fillable = [
        'title',
        'theme',
        'description',
        'mediums',
        'objective',
        'sections',
        'budget',
        'exhibition_date',
    ];

    protected $casts = [
        'sections' => 'array',
        'exhibition_date' => 'date'
    ];

    // Add these accessors for formatted values
    public function getFormattedBudgetAttribute()
    {
        return '$' . number_format($this->budget, 2);
    }

    public function getFormattedDateAttribute()
    {
        return $this->exhibition_date->format('M d, Y');
    }
}
