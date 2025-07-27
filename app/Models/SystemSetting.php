<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    use HasUlids;

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('SystemSettings');
        });
    }
}
