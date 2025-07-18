<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'api_provider_id',
        'api_service_id',
        'manual_order',
        'name',
        'type',
        'price',
        'api_price',
        'original_price',
        'min',
        'max',
        'description',
        'dripfeed',
        'refill',
        'refill_automatic',
        'status',
        'cancel',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dripfeed' => 'boolean',
        'refill' => 'boolean',
        'refill_automatic' => 'boolean',
        'status' => 'boolean',
        'cancel' => 'boolean',
    ];

    /**
     * Scope a query to only include active services.
     *
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get the category that owns the service.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    /**
     * Get the API provider that owns the service.
     */
    public function apiProvider(): BelongsTo
    {
        return $this->belongsTo(ApiProvider::class);
    }
}
