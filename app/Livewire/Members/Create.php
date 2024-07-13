<?php

namespace App\Livewire\Members;

use App\Livewire\Forms\MemberForm;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Create Member')]
class Create extends Component
{
    use WithFileUploads;

    /**
     * The form instance for creating a member.
     *
     * @var MemberForm
     */
    public MemberForm $form;

    /**
     * Save the new member data.
     *
     * @return void
     */
    public function save(): void
    {
        $this->form->store();
        $this->redirectRoute('members');
    }

    /**
     * Render the create member view.
     *
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.members.create');
    }
}

