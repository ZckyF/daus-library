<?php

namespace App\Livewire\Members;

use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Members')]
class Index extends Component
{
    use WithPagination;

    /**
     * The search term for filtering members.
     *
     * @var string
     */
    public string $search = '';

    /**
     * The sorting option for listing members.
     *
     * @var string
     */
    public string $sortBy = 'newest';

    /**
     * The number of members to display per page.
     *
     * @var int
     */
    public int $perPage = 12;

    /**
     * The ID of the member to be deleted.
     *
     * @var int
     */
    public int $memberId;

    /**
     * Flag to show delete selected button.
     *
     * @var bool
     */
    public bool $showDeleteSelected = false;

    /**
     * Array of selected member IDs.
     *
     * @var array
     */
    public array $selectedMembers = [];

    /**
     * Fetch the list of members based on search and sorting options.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function fetchMembers(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Member::query();

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('number_card', 'like', '%' . $this->search . '%')
                      ->orWhere('full_name', 'like', '%' . $this->search . '%');
            });
        }

        switch ($this->sortBy) {
            case 'fullname-asc':
                $query->orderBy('full_name', 'asc');
                break;
            case 'fullname-desc':
                $query->orderBy('full_name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
        }

        return $query->paginate($this->perPage);
    }

    /**
     * Reset the page number when the search term is updated.
     *
     * @return void
     */
    public function updatedSearch(): void 
    {
        $this->resetPage();
    }

    /**
     * Reset the page number when the sorting option is updated.
     *
     * @return void
     */
    public function updatedSortBy(): void 
    {
        $this->resetPage();
    }

    /**
     * Reset the page number when the per-page option is updated.
     *
     * @return void
     */
    public function updatedPerPage(): void 
    {
        $this->resetPage();
    }

    /**
     * Set the member ID for deletion.
     *
     * @param int $memberId
     * @return void
     */
    public function setMemberId(int $memberId): void
    {
        $this->memberId = $memberId;
    }

    /**
     * Delete the specified member.
     *
     * @return void
     */
    public function delete(): void
    {
        $member = Member::find($this->memberId);
        if (Gate::denies('delete', $member)) {
            abort(403);
        }
        $member->delete();
        $member->update(['user_id' => Auth::user()->id]);  
        session()->flash('success', 'Member successfully deleted');
    
        
        $this->dispatch('closeModal');
    }

    /**
     * Delete the selected members.
     *
     * @return void
     */
    public function deleteSelected(): void
    {
        $members = Member::find($this->selectedMembers);
        if (Gate::denies('delete', $members[0])) {
            abort(403);
        }
        foreach ($members as $member) {
            $member->delete();
            $member->update(['user_id' => Auth::user()->id]);
        }

        $this->selectedMembers = [];
        session()->flash('success', 'Members successfully deleted');
        
        $this->dispatch('closeModal');
    }

    /**
     * Update the delete selected button visibility based on selection.
     *
     * @return void
     */
    public function updatedSelectedMembers(): void
    {
        $this->showDeleteSelected = !empty($this->selectedMembers);
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $members = $this->fetchMembers();
        $optionPages = [12, 24, 48, 84, 108];
        $optionSorts = [
            'newest' => 'Newest',
            'oldest' => 'Oldest',
            'fullname-asc' => 'Name A-Z',
            'fullname-desc' => 'Name Z-A'
        ];
        return view('livewire.members.index', compact('members', 'optionPages', 'optionSorts'));
    }
}

