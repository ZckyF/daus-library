<?php

namespace App\Livewire\Settings;

use App\Livewire\Forms\Settings\ProfileForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Profile')]
class Profile extends Component
{
    use WithFileUploads;

    public ProfileForm $form;
    public function mount(): void
    {
        $user = Auth::user();
        $this->form->setProfile($user);
    }
    public function save(): void
    {
        $this->form->update();
        $this->redirectRoute('settings.profile');
    }
    public function render()
    {
        return view('livewire.settings.profile');
    }
}
