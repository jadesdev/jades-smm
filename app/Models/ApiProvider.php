<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class ApiProvider extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'api_key',
        'currency',
        'balance',
        'rate',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('api_providers_list');
        });
    }
}
