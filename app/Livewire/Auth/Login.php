<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\Auth\LoginForm;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login')]
#[Layout('layouts.auth')]
class Login extends Component
{
    public LoginForm $form;
    
    public function login() 
    {
        $this->form->store();
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
