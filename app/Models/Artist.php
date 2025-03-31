<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'website',
        'bio',
        'style',
        'is_verified'
    ];

    // Relationship to User (if using separate Artist model)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to Artworks
    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }

    // Relationship to Exhibitions
    public function exhibitions()
    {
        return $this->hasMany(Exhibition::class);
    }

    // Accessor for public contact (you can add logic here to reveal contact gradually)
    public function getPublicEmailAttribute()
    {
        // Example: Only show email if user has at least 5 artworks
        if ($this->artworks()->count() >= 5) {
            return $this->email;
        }
        return null;
    }
}
