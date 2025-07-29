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
        'email_status' => 'boolean',
        'push_status' => 'boolean',
        'inapp_status' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'channels',
        'title',
        'subject',
        'message',
        'content',
        'type',
        'content',
        'email_status',
        'subject',
        'shortcodes',
    ];
}
