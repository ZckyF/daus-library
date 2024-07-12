<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\Auth\ForgotPasswordForm;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.auth')]
#[Title('Forgot Password')]
class ForgotPassword extends Component
{
    /**
     * Form instance for handling forgot password functionality.
     * 
     * @var ForgotPasswordForm
     */
    public ForgotPasswordForm $form;

    /**
     * Send reset password link.
     * 
     * @return void
     */
    public function forgotPassword(): void
    {
        $this->form->sendResetLink();    
    }

    /**
     * Render the component view for forgot password.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.forgot-password');
    }
}
