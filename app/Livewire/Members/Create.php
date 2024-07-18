<?php

namespace App\Livewire\Members;

use App\Livewire\Forms\MemberForm;
use App\Models\Member;
use Illuminate\Support\Facades\Gate;
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
        if(Gate::denies('create', Member::class)) {
            abort(403);
        }
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

