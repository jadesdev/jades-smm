<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class NotifyTemplate extends Model
{
    use HasUlids;

    protected $casts = [
        'shortcodes' => 'object',
        'channels' => 'array',
        // 'email_status' => 'boolean',
        // 'push_status' => 'boolean',
        // 'inapp_status' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'title',
        'subject',
        'message',
        'content',
        'type',
        'shortcodes',
        'channels',
        'email_status',
        'push_status',
        'inapp_status',
    ];
}
