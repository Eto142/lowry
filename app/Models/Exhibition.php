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
        'picture_url',
        'picture_public_id',
        'title',
        'description',
        'date',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'buyer_address',
        'show_buyer_contact',
        'seller_name',
        'seller_email',
        'seller_phone',
        'seller_address',
        'show_seller_contact',
        'exhibition_status',
        'exhibition_type',
        'amount_sold',
        'is_featured',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];




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
