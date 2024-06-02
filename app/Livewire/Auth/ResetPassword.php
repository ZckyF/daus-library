<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\Auth\ResetPasswordForm;
use Livewire\Component;

class ResetPassword extends Component
{
    public ResetPasswordForm $form;
    
    public $token;
    public $email;
  
    public function mount(string $token, string $email)
    {
      $this->token = $token;
      $this->email = $email;
    }


    public function resetPassword()
    {
        $this->form->update($this->email,$this->token);
    }
    
    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
