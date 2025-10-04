<?php

namespace App\Livewire\Admin;

use App\Traits\LivewireToast;
use App\Traits\SettingsTrait;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('admin.layouts.main')]
class GeneralSettings extends Component
{
    use LivewireToast;
    use SettingsTrait;
    use WithFileUploads;

    public $view;

    public $gateways = [];

    public $sysSettings = [];

    public $settings = [];

    // meta
    public string $metaTitle = 'General Settings';

    public function mount($type = 'index'): void
    {
        $this->view = 'index';
        if ($type == 'payment') {
            $this->showPayment();
            $this->metaTitle = 'Payment Settings';
        }

        if ($type == 'features') {
            $this->view = 'features';
            $this->metaTitle = 'Features Settings';
            $features = [
                'force_https',
                'verify_email',
            ];
            foreach ($features as $feature) {
                $this->sysSettings[$feature] = (bool) sys_setting($feature);
            }
        }

        $this->settings = get_setting();
    }

    public function showPayment(): void
    {
        $this->gateways = [
            ['name' => 'Paypal', 'key' => 'paypal_payment'],
            ['name' => 'Paystack', 'key' => 'paystack_payment'],
            ['name' => 'Flutterwave', 'key' => 'flutterwave_payment'],
            ['name' => 'Korapay', 'key' => 'korapay_payment'],
            // ['name' => 'Cryptomus', 'key' => 'cryptomus_payment'],
            ['name' => 'Manual', 'key' => 'manual_payment'],
        ];

        foreach ($this->gateways as $gateway) {
            $this->sysSettings[$gateway['key']] = (bool) sys_setting($gateway['key']);
        }

        $this->view = 'payments';
    }

    public function updatedSysSettings($value, $key): void
    {
        $this->systemSetUpdate(
            (object) [
                'name' => $key,
                'value' => $value,
            ]
        );
        $this->successAlert('Settings updated successfully.', 'Settings Saved');
    }

    public function render()
    {
        return view('livewire.admin.general-settings');
    }
}
