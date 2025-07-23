<?php

namespace App\Livewire\Admin\User;

use App\Traits\LivewireToast;
use App\Traits\SettingsTrait;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('admin.layouts.main')]
class Settings extends Component
{
    use LivewireToast;
    use SettingsTrait;
    public $sysSettings = [];
    public $referral_bonus;
    public $welcome_bonus;

    // meta
    public string $metaTitle = "User Settings";


    public function mount()
    {
        $settings = [
            'is_referral',
            'is_welcome_bonus',
            'referral_bonus',
            'welcome_bonus',
        ];
        foreach ($settings as $setting) {
            if (in_array($setting, ['is_referral', 'is_welcome_bonus'])) {
                $this->sysSettings[$setting] = (bool) sys_setting($setting);
            } else {
                $this->{$setting} = sys_setting($setting);
            }
        }
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

    public function saveReferralSettings()
    {
        $this->systemSetUpdate(
            (object) [
                'name' => 'referral_bonus',
                'value' => $this->referral_bonus,
            ]
        );
        $this->successAlert('Referral settings saved successfully.', 'Settings Saved');
    }

    public function saveWelcomeBonus()
    {
        $this->systemSetUpdate(
            (object) [
                'name' => 'welcome_bonus',
                'value' => $this->welcome_bonus,
            ]
        );
        $this->successAlert('Welcome bonus settings saved successfully.', 'Settings Saved');
    }

    public function render()
    {
        return view('livewire.admin.user.settings');
    }
}
