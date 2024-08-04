<?php

namespace App\Livewire\Fines;

use App\Livewire\Forms\FineForm;
use App\Models\Fine;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Fines')]
class Edit extends Component
{
    /**
     * Instance of FineForm
     * 
     * @var FineForm
     */
    public ?FineForm $form;
    /**
     * Username of the user
     * 
     * @var string
     */
    public string $user = '';
    /**
     * Member search
     * 
     * @var string
     */
    public string $searchMember = '';
    /**
     * Non member name
     * 
     * @var string
     */
    public $nonMemberName = '';
    /**
     * Initialize the component with a fine number.
     * 
     * @param string $fine_number
     * @return void
     */
    public function mount(string $fine_number): void
    {
        $fine = Fine::where('fine_number', $fine_number)->firstOrFail();
        $this->form->setFine($fine);
        $this->nonMemberName = $fine->non_member_name;
        if(!$fine) {
            abort(404);
        }
        if(Gate::denies('update', $this->form->fine)) {
            abort(403);
        }

        $this->user = $fine->user->username;
    }
    /**
     * Edit fine.
     * 
     * @return void
     */
    public function save(): void
    {
        if(Gate::denies('update', $this->form->fine)) {
            abort(403);
        }
        $this->form->update();
        $this->redirectRoute('fines');
    }
    /**
     * Delete the current fine.
     * 
     * @return void
     */
    public function delete(): void
    {
        if(Gate::denies('delete', $this->form->fine)) {
            abort(403);
        }
        $fine = Fine::find($this->form->fine->id);
        $fine->delete();
        $fine->update(['user_id' => Auth::user()->id]);
            
        session()->flash('success', 'Fine successfully deleted');
    
        $this->redirectRoute('fines');
    }
    /**
     * Choose member for fine
     * 
     * @param int $memberId
     * @return void
     */
    public function chooseMember(int $memberId): void
    {
        if($this->form->non_member_name || $this->nonMemberName) {
            $this->form->non_member_name = '';
            $this->nonMemberName = '';
        }
        $member = Member::find($memberId);
        $this->form->selectedMember = $member->full_name;
        $this->form->member_id = $member->id;
    }
    /**
     * Update non member name
     * 
     * @return void
     */
    public function updatedNonMemberName(): void
    {
        if($this->form->selectedMember) {
            $this->form->member_id = null;
            $this->form->selectedMember = ''; 
        }
        $this->form->non_member_name = $this->nonMemberName;
    }
    /**
     * Fetch members
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetchMembers(): \Illuminate\Database\Eloquent\Collection
    {
        $query= Member::query();

        if($this->searchMember){
            $query->where('full_name', 'like', '%'.$this->searchMember.'%');
        }

        return $query->get();
    }
    public function render()
    {
        $isEditPage = true;
        $members= $this->fetchMembers();
        return view('livewire.fines.edit',compact('members','isEditPage'));
    }
}
