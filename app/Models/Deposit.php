<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deposit extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'crypto_type',
        'wallet_address',
        'status',
        'transaction_hash'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
