<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class Exhibition extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'picture',
        'title',
        'description',
        'buyer_name',
        'seller_name',
        'exhibition_status',
        'amount_sold',
        'date',
        'start_date',
        'end_date',
        'venue',
        'image_url',
        'artist_email',
        'is_featured'
    ];

    protected $dates = ['start_date', 'end_date'];

    /**
     * Get the user associated with the exhibition.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin associated with the exhibition.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the artworks associated with the exhibition.
     */
    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }

    /**
     * Get the artist associated with the exhibition.
     */
    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }
}
