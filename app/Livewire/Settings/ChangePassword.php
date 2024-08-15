<?php

namespace App\Livewire\Settings;

use App\Livewire\Forms\Settings\ChangePasswordForm;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Change Password')]
class ChangePassword extends Component
{
    /**
     * The form instance
     * 
     * @var ChangePasswordForm
     */
    public ChangePasswordForm $form;
    /**
     * Change the password
     * 
     * @return void
     */
    public function change(): void
    {
        $this->form->update();
    }
    /**
     * Render the component
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.settings.change-password');
    }
}
