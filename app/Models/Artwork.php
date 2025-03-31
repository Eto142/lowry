<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artwork extends Model
{
    protected $fillable = [
        'title',
        'description',
        'artist_id',
        'exhibition_id',
        'image_url',
        'price',
        'current_bid',
        'is_auction',
        'is_sold',
        'auction_end',
        'medium',
        'category'
    ];
    
    protected $dates = ['auction_end'];
    
    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }
    
    public function exhibition()
    {
        return $this->belongsTo(Exhibition::class);
    }
    
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
    
    public function current_bidder()
    {
        return $this->belongsTo(User::class, 'current_bidder_id');
    }
    
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}