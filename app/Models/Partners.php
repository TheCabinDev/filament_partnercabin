<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Illuminate\Notifications\Notifiable;

class Partners extends Authenticatable
{
    use HasApiTokens, Notifiable, HasPushSubscriptions, HasFactory;

    protected $table = 'partners';

    protected $fillable = [
        'creator_id',
        'name',
        'phone',
        'email',
        'password',
        'destination_bank',
        'account_number',
        'image_profile',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'auth_code'
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partnerCodes(): HasMany
    {
        return $this->hasMany(PartnersCode::class, 'id_partner');
    }

    public function claimcodereward(): HasMany
    {
        return $this->hasMany(ClaimCodeRecord::class, 'id');
    }

    public function rewardredemption(): HasMany
    {
        return $this->hasMany(RewardRedemption::class, 'id_partner');
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

    public function poinactivity(): HasMany
    {
        return $this->hasMany(PoinActivity::class, 'id_partner');
    }

    public function poinledger(): HasMany
    {
        return $this->hasMany(PoinLedger::class, 'id_partner');
    }

}
