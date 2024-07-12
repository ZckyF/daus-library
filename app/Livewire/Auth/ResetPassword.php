<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\Auth\ResetPasswordForm;
use Livewire\Component;

class ResetPassword extends Component
{
    /**
     * Form instance for handling reset password functionality.
     * 
     * @var ResetPasswordForm
     */
    public ResetPasswordForm $form;

    /**
     * Token for resetting password.
     * 
     * @var string
     */
    public $token;

    /**
     * Email address associated with the password reset request.
     * 
     * @var string
     */
    public $email;

    /**
     * Initialize component with token and email.
     * 
     * @param string $token
     * @param string $email
     * @return void
     */
    public function mount(string $token, string $email): void
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Reset the user's password.
     * 
     * @return void
     */
    public function resetPassword(): void
    {
        $this->form->update($this->email, $this->token);
    }

    /**
     * Render the component view for reset password.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.reset-password');
    }
}

