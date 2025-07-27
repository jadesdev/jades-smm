<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'title' => 'Jades SMM',
            'name' => 'Jades SMM',
            'description' => 'Default settings for Jades SMM.',
            'phone' => '+1234567890',
            'address' => '123 Main Street, City, Country',
            'admin_email' => 'admin@jades-smm.com',
            'support_email' => 'support@jades-smm.com',
            'email' => 'info@jades-smm.com',
            'favicon' => 'favicon.png',
            'logo' => 'logo.png',
            'currency' => '₦',
            'currency_code' => 'NGN',
            'currency_rate' => '1700',
            'primary' => '#3490dc',
            'secondary' => '#ffed4a',
            'custom_css' => null,
            'custom_js' => null,
            'rejected_usernames' => json_encode(['admin', 'support', 'root']),
            'last_cron' => now(),
        ]);
    }
}
