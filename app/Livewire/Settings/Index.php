<?php

namespace App\Livewire\Settings;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Settings')]
class Index extends Component
{
    public function render()
    {
        $settings = [
            [
                'icon' => 'bi-person-fill',
                'icon_color' => 'text-primary',
                'title' => 'Profile',
                'subtitle' => 'Change your profile to be better',
            ],
            [
                'icon' => 'bi-lock-fill',
                'icon_color' => 'text-warning',
                'title' => 'Reset Password',
                'subtitle' => 'Update your password regularly',
            ],
            [
                'icon' => 'bi-translate',
                'icon_color' => 'text-success',
                'title' => 'Language',
                'subtitle' => 'Change application language',
            ],
        ];
        
        return view('livewire.settings.index',compact('settings'));
    }
}
