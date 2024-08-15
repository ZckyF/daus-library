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
    /**
     * The form instance.
     * 
     * @var ProfileForm
     */
    public ProfileForm $form;
    /**
     * Mount the component with the user's profile data.
     * 
     * @return void
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->form->setProfile($user);
    }
    /**
     * Save the profile data
     * 
     * @return void
     */
    public function save(): void
    {
        $this->form->update();
        $this->redirectRoute('settings.profile');
    }
    /**
     * Render the profile view
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.settings.profile');
    }
}
