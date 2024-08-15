<?php

namespace App\Livewire\Forms\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ChangePasswordForm extends Form
{
    /**
     * The old password of the user.
     * 
     * @var string
     */
    public $old_password;
    /**
     * The new password of the user.
     * 
     * @var string
     */
    public $new_password;
    /**
     * The confirm password of the user.
     * 
     * @var string
     */
    public $confirm_password;
    /**
     * Change the password user
     * 
     * @return void
     */
    public function update(): void
    {
        $this->validate([
            'old_password' => [
                'required',
                'string',
                'max:255',
                'min:8',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
            'new_password' => 'required|string|max:255|min:8',
            'confirm_password' => 'required|string|max:255|min:8',
        ]);
    
        if ($this->new_password !== $this->confirm_password) {
            $this->addError('confirm_password', 'The new password and confirm password do not match.');
            return;
        }
    
        Auth::user()->update([
            'password' => Hash::make($this->new_password)
        ]);
    
        $this->reset();
        session()->flash('success', 'Password successfully updated.');
    }
}
