<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $fillable = [
        'artwork_id',
        'user_id',
        'amount',
        'is_anonymous'
    ];

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }

    public function bidder()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
