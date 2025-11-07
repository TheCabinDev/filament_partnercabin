<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class Partners extends Model
{
    use HasFactory;

    protected $table = 'partners';

    protected $fillable = [
        'creator_id',
        'name',
        'email',
        'password',
        'image_profile',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'creator_id' => 'integer',
    ];

    public function setPasswordAttribute($value): void
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::needsRehash($value)
                ? Hash::make($value)
                : $value;
        }
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'INACTIVE');
    }

    public function isActive(): bool
    {
        return $this->status === 'ACTIVE';
    }

    public function isInactive(): bool
    {
        return $this->status === 'INACTIVE';
    }
}
