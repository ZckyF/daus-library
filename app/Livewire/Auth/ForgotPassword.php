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
    public ForgotPasswordForm $form;

    public function forgotPassword() 
    {
        $this->form->sendResetLink();    
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
