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
    /**
     * Form instance for handling login functionality.
     * 
     * @var LoginForm
     */
    public LoginForm $form;

    /**
     * Process user login.
     * 
     * @return void
     */
    public function login(): void
    {
        $this->form->store();
    }

    /**
     * Render the component view for login.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.login');
    }
}

