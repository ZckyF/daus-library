<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login')]
#[Layout('layouts.auth')]
class Login extends Component
{
    public function render()
    {
        return view('livewire.auth.login');
    }
}
