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

    public MemberForm $form;

    public function save()
    {
        $this->form->store();
        $this->redirectRoute('members');
    }
    public function render()
    {
        return view('livewire.members.create');
    }
}
