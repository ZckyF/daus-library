<?php

namespace App\Livewire\Members;

use App\Livewire\Forms\MemberForm;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Edit Member')]
class Edit extends Component
{
    use WithFileUploads;

    public $memberId;
    public $user;

    public MemberForm $form;

    public function mount($number_card)
    {
        $member = Member::where('number_card', $number_card)->firstOrFail();

        if (!$member) {
            abort(404);
        }

        $this->memberId = $member->id;
        $this->user = $member->user->username;

        $this->form->number_card = $member->number_card;
        $this->form->full_name = $member->full_name;
        $this->form->email = $member->email;
        $this->form->phone_number = $member->phone_number;
        $this->form->address = $member->address;
        $this->form->image_name = $member->image_name;

    }

    public function save()
    {
        $this->form->update($this->memberId);
        $this->redirectRoute('members');
    }
    public function delete()
    {
        $member = Member::find($this->memberId);
    
        $coverImage = $member->cover_image_name;
    
        $member->delete();
    
        if ($coverImage && $coverImage !== 'default.jpg') {
            Storage::disk('public')->delete('members/' . $coverImage);
        }
    
        session()->flash('success', 'Member deleted successfully');
            
        $this->redirectRoute('members');
        
    }

    public function render()
    {
        $isEditPage = true;
        return view('livewire.members.edit',compact('isEditPage'));
    }
}
