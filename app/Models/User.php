<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, HasUlids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'image',
        'country',
        'address',
        'password',
        'ref_id',
        'email_verify',
        'sms_verify',
        'is_active',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token'
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
            'email_verify' => 'boolean',
            'sms_verify' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'ref_id');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'ref_id');
    }

    /**
     * Get all support tickets for this user
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Get all support messages for this user
     */
    public function supportMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
    }

    /**
     * Get open support tickets for this user
     */
    public function openSupportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class)->open();
    }

    /**
     * Get all transactions for this user
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all deposits for this user
     */
    public function deposits(): HasMany
    {
        return $this->hasMany(Transaction::class)->where('type', 'deposit');
    }

    /**
     * Get all orders for this user
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
