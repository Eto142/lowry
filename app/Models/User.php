<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'country',
        'phone_number',
        'avatar',
        'kyc_status',
        'id_type',
        'front_id',
        'back_id',
        'email',
        'verification_code',
        'verification_expiry',
        'password',
        'access',
        'withdrawal_access',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userBalance()
    {
        return $this->hasOne(Balance::class);
    }
    // ✅ Relationship to Balance
    public function verifiedPaymentMethods()
    {
        return $this->hasMany(PaymentMethod::class)->where('is_verified', true);
    }

    public function balance()
    {
        return $this->hasOne(Balance::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}
