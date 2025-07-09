<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SupportTicket extends Model
{
    use HasUlids;

    protected $fillable = [
        'user_id',
        'code',
        'subject',
        'status',
        'meta',
        'last_reply',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    const STATUS_OPEN = 'open';

    const STATUS_PENDING = 'pending';

    const STATUS_RESOLVED = 'resolved';

    const STATUS_CLOSED = 'closed';

    public static function getStatuses()
    {
        return [
            self::STATUS_OPEN => 'Open',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', [self::STATUS_OPEN, self::STATUS_PENDING]);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for user's tickets
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function isOpen(): bool
    {
        return in_array($this->status, [self::STATUS_OPEN, self::STATUS_PENDING]);
    }

    /**
     * Get messages ordered by creation date
     */
    public function orderedMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get the latest message
     */
    public function latestMessage(): HasOne
    {
        return $this->hasOne(SupportMessage::class, 'ticket_id')
            ->latest();
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(SupportMessage::class, 'ticket_id')->textMessages()->latest();
    }

    /**
     * Get the first message (initial message)
     */
    public function firstMessage(): HasOne
    {
        return $this->hasOne(SupportMessage::class, 'ticket_id')
            ->oldest();
    }

    public function markAsReplied(string $by = 'user'): void
    {
        $this->update([
            'last_reply' => $by,
            'status' => $by === 'admin' ? self::STATUS_RESOLVED : self::STATUS_OPEN,
        ]);
    }

    /**
     * Get status badge color
     */
    protected function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_OPEN => 'green',
            self::STATUS_PENDING => 'yellow',
            self::STATUS_RESOLVED => 'blue',
            self::STATUS_CLOSED => 'gray',
            default => 'gray'
        };
    }

    /**
     * Generate unique ticket code
     */
    public static function generateCode(): string
    {
        $year = date('Y');
        $prefix = "TKT-{$year}-";

        return \DB::transaction(function () use ($prefix) {
            $lastTicket = self::where('code', 'like', $prefix.'%')
                ->lockForUpdate()
                ->orderBy('code', 'desc')
                ->first();

            if ($lastTicket) {
                $lastNumber = (int) substr($lastTicket->code, strlen($prefix));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            return $prefix.str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        });
    }
}
