<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FutureExhibition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'theme',
        'description',
        'mediums',
        'objective',
        'sections',
        'budget',
        'exhibition_date',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'exhibition_date' => 'date',
        'sections' => 'array', // If storing JSON in the sections field
        'budget' => 'decimal:2',
    ];

    /**
     * Validation rules for the model.
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'theme' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'mediums' => 'nullable|string|max:255',
            'objective' => 'nullable|string',
            'sections' => 'nullable|array',
            'budget' => 'nullable|numeric',
            'exhibition_date' => 'nullable|date',
            'type' => 'required|in:future,current',
        ];
    }

    /**
     * Get the allowed types for the exhibition.
     *
     * @return array
     */
    public static function getTypes()
    {
        return ['future', 'current'];
    }

    // app/Models/FutureExhibition.php

    // Add this method to your model
    public function getFormattedBudgetAttribute()
    {
        if ($this->budget) {
            return '$' . number_format($this->budget, 2);
        }
        return 'Not specified';
    }
}
