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
}
