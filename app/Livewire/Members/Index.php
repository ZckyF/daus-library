<?php

namespace App\Livewire\Members;

use App\Models\Member;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Members')]
class Index extends Component
{
    use WithPagination;
    public $search = '';
    public $sortBy = 'newest';
    public $perPage = 12;
    public $memberId;
    public $showDeleteSelected = false;
    public $selectAllCheckbox = false;
    public $selectedMembers = [];

    public function fetchBooks()
    {
        
        $query = Member::query();

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('number_card', 'like', '%' . $this->search . '%')
                      ->orWhere('full_name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->sortBy == 'fullname-asc') {
            $query->orderBy('full_name', 'asc');
        } elseif ($this->sortBy == 'fullname-desc') {
            $query->orderBy('full_name', 'desc');
        } elseif ($this->sortBy == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($this->sortBy == 'oldest') {
            $query->orderBy('created_at', 'asc');
        }

        return $query->paginate($this->perPage);
    }
    public function updatedSearch() 
    {
        $this->resetPage();
    }
    public function updatedSortBy() 
    {
        $this->resetPage();
    }

    public function updatedPerPage() 
    {
        $this->resetPage();
    }
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;
    }
    public function delete()
    {
       Member::destroy($this->memberId);
       session()->flash('success', 'Member successfully deleted.');

       $this->dispatch('closeModal');
    }
    public function deleteSelected()
    {
        Member::destroy($this->selectedMembers);
        $this->selectedMembers = [];
        session()->flash('success', 'Members successfully deleted.');
        $this->dispatch('closeModal');
    }

    public function toggleSelectAll()
    {
        if ($this->selectAllCheckbox) {
            $this->selectedMembers = Member::pluck('id')->toArray();
            $this->showDeleteSelected = true;
        } else {
            $this->selectedMembers = [];
            $this->showDeleteSelected = false;
        }
    }

    public function updatedSelectedMembers()
    {
        if ($this->selectedMembers) {
            $this->showDeleteSelected = true;
        } else {
            $this->showDeleteSelected = false;
        }
    }
    
    public function render()
    {
        $members = $this->fetchBooks();
        $optionPages = ['12','24','48','84','108'];
        $optionSorts = [
            'newest' => 'Newest',
            'oldest' => 'Oldest',
            'fullname-asc' => 'Name A-Z',
            'fullname-desc' => 'Name Z-A'
        ];
        return view('livewire.members.index', compact('members', 'optionPages','optionSorts'));
    }
}
