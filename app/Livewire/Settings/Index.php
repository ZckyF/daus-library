<?php

namespace App\Livewire\Settings;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Settings')]
class Index extends Component
{
    /**
     * Render the component.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $settings = [
            [
                'route' => 'profile',
                'icon' => 'bi-person-fill',
                'icon_color' => 'text-primary',
                'title' => 'Profile',
                'subtitle' => 'Change your profile to be better',
            ],
            [
                'route' => 'change-password',
                'icon' => 'bi-lock-fill',
                'icon_color' => 'text-warning',
                'title' => 'Change Password',
                'subtitle' => 'Update your password regularly',
            ],
        ];
        
        return view('livewire.settings.index',compact('settings'));
    }
}
