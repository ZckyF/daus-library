<?php

namespace App\Livewire\Forms\Auth;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ResetPasswordForm extends Form
{
    #[Rule('required|email|exists:employees,email|max:255')]
    public string $email = '';
    #[Rule('required|string')]
    public string $token = '';
    #[Rule('required|string|min:8|max:255|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    


    public function update($email,$token)
    {
        $this->email = $email;
        $this->token = $token;
        $this->validate();

        $reset = DB::table('password_reset_tokens')->where('email', $this->email)->first();

        if (!$reset || !Hash::check($this->token, $reset->token)) {
            throw ValidationException::withMessages([
                'email' => ['The reset token is invalid or has expired.'],
            ]);
        }

        $employee = Employee::where('email', $this->email)->first();

        $user = User::where('employee_id', $employee->id)->first();
        $user->password = $this->password;
        $user->save();

        DB::table('password_reset_tokens')->where('email', $this->email)->delete();

        session()->flash('success', 'Your password has been reset. You can log in with the new password.');
        return redirect()->route('login');
        
    }
}
