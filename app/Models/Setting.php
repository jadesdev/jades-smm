<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasUlids;

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('Setting');
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'email',
        'admin_email',
        'support_email',
        'name',
        'description',
        'address',
        'phone',
        'logo',
        'favicon',
        'primary',
        'secondary',
        'last_cron',
        'custom_js',
        'custom_css',
        'currency',
        'currency_code',
        'currency_rate',
        'rejected_usernames',
        'twitter',
        'facebook',
        'instagram',
        'telegram',
        'whatsapp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'shortcodes' => 'object',
    ];
}
