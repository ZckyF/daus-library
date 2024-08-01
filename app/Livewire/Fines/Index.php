<?php

namespace App\Livewire\Fines;

use App\Models\Fine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Fines')]
class Index extends Component
{
    use WithPagination;

    /**
     * Search term for fines.
     * 
     * @var string
     */
    public string $search = '';

    /**
     * Sorting criteria for fines.
     * 
     * @var string
     */
    public string $sortBy = 'newest';

    /**
     * Number of items per page for pagination.
     * 
     * @var int
     */
    public int $perPage = 10;

    /**
     * ID of the fines to be deleted.
     * 
     * @var int
     */
    public int $fineId;

    /**
     * Array of selected fine IDs.
     * 
     * @var array
     */
    public array $selectedFines = [];

    /**
     * Flag to show or hide the delete selected button.
     * 
     * @var bool
     */
    public bool $showDeleteSelected = false;

    /**
     * Reset pagination when the search term is updated.
     * 
     * @return void
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when the sorting criteria is updated.
     * 
     * @return void
     */
    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when the items per page is updated.
     * 
     * @return void
     */
    public function updatedPerPage(): void
    {
        $this->resetPage();     
    }

    /**
     * Update the showDeleteSelected flag when selected fines are updated.
     * 
     * @return void
     */
    public function updatedSelectedFines(): void
    {
        $this->showDeleteSelected = !empty($this->selectedFines);
    }

    /**
     * Set the fine ID to be deleted.
     * 
     * @param int $fineId
     * @return void
     */
    public function setFineId(int $fineId): void
    {
        $this->fineId = $fineId;
    }

    /**
     * Delete a fine by its ID.
     * 
     * @return void
     */
    public function delete(): void
    {
        $fine = Fine::find($this->fineId);
        if (Gate::denies('delete', $fine)) {
            abort(403);
        }
        $fine->delete();
        $fine->update(['user_id' => Auth::user()->id]);
            
        session()->flash('success', 'Fine successfully deleted');
    
        $this->dispatch('closeModal');
        
    }

    /**
     * Delete selected fines.
     * 
     * @return void
     */
    public function deleteSelected(): void
    {
        $fines = Fine::find($this->selectedFines);
        if (Gate::denies('delete', $fines[0])) {
            abort(403);
        }
        foreach ($fines as $fine) {
            $fine->delete();
            $fine->update(['user_id' => Auth::user()->id]);
        }

        $this->selectedFines = [];
        session()->flash('success', 'Fines successfully deleted');
        $this->dispatch('closeModal');
    }

    /**
     * Fetch fine data.
     * 
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function fetchFines(): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Fine::query();

        if ($this->search) {
            $query->where('fine_number', 'like', '%' . $this->search . '%')
                    ->orWhere('non_member_name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('member', function ($query) {
                        $query->where('full_name', 'like', '%' . $this->search . '%');
                    });
        }
        
        $query->join('members', 'fines.member_id', '=', 'members.id')
             ->select('fines.*', 'members.full_name as member_full_name');
  
        switch ($this->sortBy) {
            case 'member-asc':
                $query->orderBy('member_full_name', 'asc');
                break;
            case 'member-desc':
                $query->orderBy('member_full_name', 'desc');
                break;
            case 'non-member-asc':
                $query->orderBy('non_member_name', 'asc');
                break;
            case 'non-member-desc':
                $query->orderBy('non_member_name', 'desc');
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
    public function render()
    {
        $fines = $this->fetchFines();
        $optionPages = [10, 20, 40, 50, 100];
        $columns = ['','#','Number Fine','Member Name','Non Member Name','Amount','Amount Paid','Is Paid','Added or Edited','Actions'];
        $optionSorts = [
            'newest' => 'Newest',
            'oldest' => 'Oldest',
            'member-asc' => 'Member A-Z',
            'member-desc' => 'Member Z-A',
            'non-member-asc' => 'Non Member A-Z',
            'non-member-desc' => 'Non Member Z-A',
        ];
        return view('livewire.fines.index',compact('fines','optionPages','columns','optionSorts'));
    }
}
