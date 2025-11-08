<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'password' => 'hashed',
    ];

    public function setPasswordAttribute($value): void
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::needsRehash($value)
                ? Hash::make($value)
                : $value;
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partnerCodes(): HasMany
    {
        return $this->hasMany(PartnersCode::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(ClaimCodeRecord::class, 'id_partner');
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
