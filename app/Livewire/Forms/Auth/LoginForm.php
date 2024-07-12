<?php

namespace App\Livewire\Forms\Auth;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Form;

class LoginForm extends Form
{
    /**
     * Username or email for login.
     * 
     * @var string
     */
    #[Rule('required|string|max:255')]
    public $usernameOrEmail = '';
    
    /**
     * Password for login.
     * 
     * @var string
     */
    #[Rule('required|string|min:8|max:255')]
    public $password = '';

    /**
     * Validate user credentials and perform login.
     * 
     * @return void
     */
    public function store(): void
    {
        $this->validate();

        if (filter_var($this->usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            $employee = Employee::where('email', $this->usernameOrEmail)->first();

            if ($employee) {
                $user = User::where('employee_id', $employee->id)->first();
            } else {
                session()->flash('error', 'The provided credentials do not match records.');
                return;
            }
        } else {
            $user = User::where('username', $this->usernameOrEmail)->first();
        }

        if ($user && Auth::attempt(['username' => $user->username, 'password' => $this->password])) {
            session()->flash('message', 'Login successful.');
            return redirect()->intended();
        } else {
            session()->flash('error', 'The provided credentials do not match records.');
        }
    }
}

