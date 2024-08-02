<?php

namespace App\Livewire\Fines;

use App\Livewire\Forms\FineForm;
use App\Models\Fine;
use App\Models\Member;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Create Fines')]
class Create extends Component
{
    /**
     * Instance of FineForm
     * 
     * @var FineForm
     */
    public ?FineForm $form;
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
    public string $nonMemberName = '';
    /**
     * Save the new fine.
     * 
     * @return void
     */
    public function save(): void
    {
        if(Gate::denies('create', Fine::class)) {
            abort(403);
        }
        $this->form->store();
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
    /**
     * Render the component
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $members = $this->fetchMembers();
        return view('livewire.fines.create',compact('members'));
    }
}
