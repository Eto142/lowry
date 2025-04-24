<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KycVerification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'id_type',
        'id_number',
        'front_document_path',
        'back_document_path',
        'selfie_document_path',
        'status',
        'rejection_reason',
        'verified_at',
        'verified_by',
        'expiry_date',
        'country'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'expiry_date' => 'date'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helpers
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getFrontDocumentUrl()
    {
        return Storage::url($this->front_document_path);
    }

    public function getBackDocumentUrl()
    {
        return $this->back_document_path ? Storage::url($this->back_document_path) : null;
    }

    public function getSelfieDocumentUrl()
    {
        return $this->selfie_document_path ? Storage::url($this->selfie_document_path) : null;
    }
}