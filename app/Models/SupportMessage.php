<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Storage;

class SupportMessage extends Model
{
    use HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'type',
        'image',
        'is_admin',
    ];
    const TYPE_TEXT = 'text';
    const TYPE_IMAGE = 'image';

    /**
     * Get the ticket this message belongs to
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    /**
     * Get the user who sent this message
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sender name (user or admin)
     */
    public function getSenderName(): string
    {
        if ($this->is_admin) {
            return $this->user ? $this->user->name . ' (Admin)' : 'Admin';
        }

        return $this->user ? $this->user->name : 'User';
    }

    /**
     * Check if message has an image
     */
    public function hasImage(): bool
    {
        return $this->type === self::TYPE_IMAGE && !empty($this->image);
    }

    /**
     * Get full image URL
     */
    public function getImageUrl(): ?string
    {
        if (!$this->hasImage()) {
            return null;
        }

        return Storage::url('support/' . $this->image);
    }

    /**
     * Check if message is from admin
     */
    public function isFromAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Check if message is from user
     */
    public function isFromUser(): bool
    {
        return !$this->is_admin;
    }

    /**
     * Scope for admin messages
     */
    public function scopeAdminMessages($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope for user messages
     */
    public function scopeUserMessages($query)
    {
        return $query->where('is_admin', false);
    }

    /**
     * Scope for text messages
     */
    public function scopeTextMessages($query)
    {
        return $query->where('type', self::TYPE_TEXT);
    }

    /**
     * Scope for image messages
     */
    public function scopeImageMessages($query)
    {
        return $query->where('type', self::TYPE_IMAGE);
    }
}
