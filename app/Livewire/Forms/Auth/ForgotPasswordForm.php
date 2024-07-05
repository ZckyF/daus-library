<?php

namespace App\Livewire\Forms\Auth;


use Livewire\Form;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordForm extends Form
{
    #[Rule('required|email|max:255|exists:employees,email')]
    public string $email = '';
    
    public function sendResetLink() 
    {
        $this->validate();
        
        // Generate a token
        $token = Str::random(60);

        // Save the token and email to the password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $this->email],
            [
                'email' => $this->email,
                'token' => bcrypt($token),
                'created_at' => now()
            ]
        );

        // Send reset link to the user's email
        $resetLink = url('/reset-password/' . urlencode($this->email)) . '/' . $token  ;

        Mail::send('livewire.auth.emails.reset-password', ['link' => $resetLink], function ($message) {
            $message->to($this->email);
            $message->subject('Reset Password');
        });

        session()->flash('success', 'If the email you entered is registered, you will receive an email with a password reset link.');
    }
}
