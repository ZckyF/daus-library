<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.auth')]
#[Title('Forgot Password')]
class ForgotPassword extends Component
{
    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
