<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class ShortcodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = Setting::first();
        if (! $setting) {
            $this->call([
                SettingSeeder::class,
            ]);
        }

        $shortcodes = [
            'date' => 'Current date',
            'time' => 'Current time',
            'currency' => 'Currency symbol',
            'datetime' => 'Current date time',
            'site_name' => 'Site name',
            'site_email' => 'Site email address',
            'site_phone' => 'Site phone number',
            'site_address' => 'Site address',
            'support_email' => 'Support email',
        ];

        $setting = Setting::first();

        if ($setting) {
            $setting->update([
                'shortcodes' => ($shortcodes),
            ]);
        }
    }
}
