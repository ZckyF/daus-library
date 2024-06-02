<?php

namespace App\Livewire\Forms\Auth;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Rule('required|string|max:255')]
    public string $usernameOrEmail = '';
    
    #[Rule('required|string|min:8|max:255')]
    public string $password = '';


    public function store() 
    {
        $this->validate();

        if (filter_var($this->usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            
            $employee = Employee::where('email', $this->usernameOrEmail)->first();

            if ($employee) {
                
                $user = User::where('employee_id', $employee->id)->first();
            } else {
                session()->flash('error', 'The provided credentials does not match records.');
                return;
            }
        } else {
            $user = User::where('username', $this->usernameOrEmail)->first();
        }

        if ($user && Auth::attempt(['username' => $user->username, 'password' => $this->password])) {
            session()->flash('message', 'Login successful.');
            return redirect()->intended();
        } else {
            session()->flash('error', 'The provided credentials does not match records.');
            
        }
    }
}
