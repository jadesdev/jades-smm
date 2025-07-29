<?php

use App\Models\Setting;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

if (! function_exists('static_asset')) {
    function static_asset(string $path, $secure = null)
    {
        if (PHP_SAPI == 'cli-server') {
            return app('url')->asset('assets/' . $path, $secure);
        }

        return app('url')->asset('public/assets/' . $path, $secure);
    }
}

// Return file uploaded via uploader
if (! function_exists('my_asset')) {
    function my_asset(?string $path, $secure = null)
    {
        if (PHP_SAPI == 'cli-server') {
            return app('url')->asset('uploads/' . $path, $secure);
        }

        return app('url')->asset('public/uploads/' . $path, $secure);
    }
}

if (! function_exists('get_setting')) {
    function get_setting($key = null, $default = null)
    {
        // Check if the settings table exists
        if (! Schema::hasTable('settings')) {
            return $default;
        }

        $settings = Cache::get('Settings');

        if (! $settings) {
            $settings = Setting::first();
            if ($settings) {
                Cache::put('Settings', $settings, 30000);
            }
        }

        if ($key) {
            return @$settings->$key == null ? $default : @$settings->$key;
        }

        return $settings;
    }
}

if (! function_exists('sys_setting')) {
    function sys_setting($key, $default = null)
    {
        // // Check if the system_settings table exists
        if (! Schema::hasTable('system_settings')) {
            return $default;
        }

        $settings = Cache::get('SystemSettings');

        if (! $settings) {
            $settings = SystemSetting::all();
            Cache::put('SystemSettings', $settings, 30000);
        }

        $setting = $settings->where('name', $key)->first();

        return $setting == null ? $default : $setting->value;
    }
}

// formats currency
if (! function_exists('format_price')) {
    function format_price($price): string
    {
        $fomated_price = number_format($price, 2);
        $currency = get_setting('currency');

        return $currency . $fomated_price;
    }
}

// NGN Currency Formats
if (! function_exists('ngnformat_price')) {
    function ngnformat_price($price): string
    {
        $fomated_price = number_format($price, 2);
        $currency = '₦';

        return $currency . $fomated_price;
    }
}

function sym_price($price): string
{
    $fomated_price = number_format($price, 2);
    $currency = get_setting('currency_code');

    return $currency . ' ' . $fomated_price;
}

function format_number($price, $place = 2): string
{
    return number_format($price, $place);
}

function formatNumber($number)
{
    if ($number >= 1000000) {
        return number_format($number / 1000000, 1) . 'M';
    } elseif ($number >= 1000) {
        return number_format($number / 1000, 1) . 'K';
    }

    return number_format($number);
}
// Trim text and append ellipsis if needed
function textTrim($string, $length = null)
{
    if (empty($length)) {
        $length = 100;
    }

    return Str::limit($string, $length, '...');
}

// Trim text without appending ellipsis
function text_trimer($string, $length = null)
{
    // Set default length to 100 if not provided
    if (empty($length)) {
        $length = 100;
    }

    return Str::limit($string, $length);
}

// Generate a random alphanumeric string of a specified length
function getTrx($length = 15): string
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0';
    $charactersLength = strlen($characters);
    $randomString = '';
    // Generate a random string by selecting characters from the given set
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}

function getTrans(string $prefix, $len = 15): string
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $len; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $prefix . '_' . $randomString;
}

// Round the given amount to a specified number of decimal places
function getAmount($amount, $length = 2): float
{
    // Ensure the returned amount is treated as a numeric value
    return round($amount, $length);
}

// Format and display a datetime using Carbon library
function show_datetime($date, $format = 'Y-m-d h:ia'): string
{
    return Carbon::parse($date)->format($format);
}

// Format and display a datetime using Carbon library
function show_date($date, $format = 'Y-m-d'): string
{
    return Carbon::parse($date)->format($format);
}

function trans_date($date, $format = 'M d, Y'): string
{
    return Carbon::parse($date)->format($format);
}

// Format and display a time
function show_time($date, $format = 'h:ia'): string
{
    return Carbon::parse($date)->format($format);
}

function campaignDate($date, $format = 'M, d'): string
{
    return Carbon::parse($date)->format($format);
}

function diffForHumans($date): string
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);

    return Carbon::parse($date)->diffForHumans();
}

function custom_text($string): string
{
    return ucfirst(str_replace('_', ' ', $string));
}

function getNumber($length = 6): string
{
    // if ($length == 6) {
    //     return 123456;
    // }
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}

function getPaginate(): int
{
    return 50;
}

function paginateLinks($data)
{
    return $data->appends(request()->all())->links();
}

function queryBuild(string $key, $value): ?string
{
    $queries = request()->query();

    $delimeter = count($queries) > 0 ? '&' : '?';

    if (request()->has($key)) {
        $url = request()->getRequestUri();
        $pattern = "\?{$key}";
        $match = preg_match("/{$pattern}/", $url);

        if ($match != 0) {
            return preg_replace('~(\?|&)' . $key . '[^&]*~', "\?{$key}={$value}", $url);
        }

        $filteredURL = preg_replace('~(\?|&)' . $key . '[^&]*~', '', $url);

        return $filteredURL . $delimeter . "{$key}={$value}";
    }

    return request()->getRequestUri() . $delimeter . "{$key}={$value}";
}

function getPaymentMethodLabel($method)
{
    $paymentMethods = [
        'paystack_payment' => 'Paystack',
        'flutterwave_payment' => 'Flutterwave',
        'paypal_payment' => 'PayPal',
        'cryptomus_payment' => 'Cryptomus',
        'manual_payment' => 'Bank Transfer',
    ];

    return $paymentMethods[$method] ?? ucfirst(str_replace('_', ' ', $method));
}

function getPaymentStatusClass($status)
{
    return [
        'pending' => 'bg-warning',
        'completed' => 'bg-success',
        'failed' => 'bg-danger',
    ][$status] ?? 'bg-secondary';
}

function getPaymentStatusLabel($status)
{
    return [
        'pending' => 'Pending',
        'completed' => 'Completed',
        'failed' => 'Failed',
    ][$status] ?? 'Pending';
}

function getOrderStatusClass($status)
{
    return [
        'pending' => 'bg-warning',
        'processing' => 'bg-info',
        'completed' => 'bg-success',
        'cancelled' => 'bg-secondary',
        'failed' => 'bg-danger',
    ][$status] ?? 'bg-secondary';
}

if (! function_exists('render_sortable_header')) {
    function render_sortable_header(string $field, string $label, string $currentSortField, string $currentSortDirection): string
    {
        $icon = $currentSortField === $field
            ? ($currentSortDirection === 'asc' ? '↑' : '↓')
            : '';

        $iconHtml = $icon ? '<span class="text-primary-500 dark:text-primary-400">' . $icon . '</span>' : '';

        return <<<HTML
            <th wire:click="sortBy('$field')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                <div class="flex items-center space-x-1">
                    <span>$label</span>
                    $iconHtml
                </div>
            </th>
        HTML;
    }
}

function formatOrderStatus($status)
{
    $status = str_replace('_', '', $status);
    $status = str_replace(' ', '', $status);
    return strtolower($status);
}
