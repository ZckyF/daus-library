<?php

namespace App\Livewire\Members;

use App\Livewire\Forms\MemberForm;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Edit Member')]
class Edit extends Component
{
    use WithFileUploads;

    /**
     * The username of the member.
     *
     * @var string
     */
    public string $user;

    /**
     * The form instance for editing the member.
     *
     * @var MemberForm
     */
    public MemberForm $form;

    /**
     * Mount the component with the given member's number card.
     *
     * @param string $number_card
     * @return void
     */
    public function mount(string $number_card): void
    {
        $member = Member::where('number_card', $number_card)->firstOrFail();

        if (!$member) {
            abort(404);
        }
        if(Gate::denies('update', $member)) {
            abort(403);
        }

        $this->user = $member->user->username;

        $this->form->setMember($member);
    }

    /**
     * Save the member data.
     *
     * @return void
     */
    public function save(): void
    {
        if(Gate::denies('update', $this->form->member)) {
            abort(403);
        }
        $this->form->update();
        $this->redirectRoute('members');
    }

    /**
     * Delete the member and its cover image.
     *
     * @return void
     */
    public function delete(): void
    {
        if(Gate::denies('delete', $this->form->member)) {
            abort(403);
        }
        $member = $this->form->member;
    
        $coverImage = $member->cover_image_name;
    
        $member->delete();
        $member->update(['user_id' => Auth::user()->id]);
    
        if ($coverImage && $coverImage !== 'default.jpg') {
            Storage::disk('public')->delete('members/' . $coverImage);
        }
    
        session()->flash('success', 'Member deleted successfully');
            
        $this->redirectRoute('members');
    }

    /**
     * Render the edit member view.
     *
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $isEditPage = true;
        return view('livewire.members.edit', compact('isEditPage'));
    }
}

