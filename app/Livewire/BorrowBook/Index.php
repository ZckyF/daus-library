<?php

namespace App\Livewire\BorrowBook;

use App\Models\BorrowBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Borrow Books')]
class Index extends Component
{
    use WithPagination;

    /**
     * Search term for borrow books.
     * 
     * @var string
     */
    public string $search = '';
    /**
     * Sorting criteria for borrow books.
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
     * ID of the borrow book to be deleted.
     * 
     * @var int
     */
    public int $borrowBookId;

    /**
     * Array of selected borrow book IDs.
     * 
     * @var array
     */
    public array $selectedBorrowBooks = [];

    /**
     * Flag to show or hide the delete selected button.
     * 
     * @var bool
     */
    public bool $showDeleteSelected = false;

    public $borrowDateFrom = '';
    public $borrowDateTo = '';
    public $returnDateFrom = '';
    public $returnDateTo = '';
    public $returnedDateFrom = '';
    public $returnedDateTo = '';

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
        $this->dispatch('resetTooltip');
        
    }

    /**
     * Update the showDeleteSelected flag when selected book categories are updated.
     * 
     * @return void
     */
    public function updatedSelectedBorrowBooks(): void
    {
        $this->showDeleteSelected = !empty($this->selectedBorrowBooks);
    }

    /**
     * Set the borrow book ID to be deleted.
     * 
     * @param int $borrowBookId
     * @return void
     */
    public function setBorrowBookId(int $borrowBookId): void
    {
        $this->borrowBookId = $borrowBookId;
    }

    /**
     * Delete a borrow book by its ID.
     * 
     * @return void
     */
    public function delete(): void
    {
        $borrowBook = BorrowBook::find($this->borrowBookId);
        if (Gate::denies('delete', $borrowBook)) {
            abort(403);
        }
        $borrowBook->delete();
        $borrowBook->update(['user_id' => Auth::user()->id]);
            
        session()->flash('success', 'Borrow book successfully deleted');
    
        $this->dispatch('closeModal');
        
    }

    /**
     * Delete selected borrow books.
     * 
     * @return void
     */
    public function deleteSelected(): void
    {
        $borrowBooks = BorrowBook::find($this->selectedBorrowBooks);
        if (Gate::denies('delete', $borrowBooks[0])) {
            abort(403);
        }
        foreach ($borrowBooks as $borrowBooks) {
            $borrowBooks->delete();
            $borrowBooks->update(['user_id' => Auth::user()->id]);
        }

        $this->selectedBorrowBooks = [];
        session()->flash('success', 'Borrow books successfully deleted');
        $this->dispatch('closeModal');
    }
    /**
     * Filter the borrow books by date.
     * 
     * @return void
     */
    public function filterDate(): void
    {
        $this->dispatch('closeModal');
    }
    /**
     * Reset the filter criteria.
     * 
     * @return void
     */
    public function resetFilter(): void
    {
        $this->reset(['borrowDateFrom','borrowDateTo','returnDateFrom','returnDateTo','returnedDateFrom','returnedDateTo']);
    }
    /**
     * Fetch borrow books based on search, sorting, and pagination criteria.
     * 
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function fetchBorrowBooks(): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = BorrowBook::query();

        if ($this->search) {
            $query->where('borrow_number', 'like', '%' . $this->search . '%')
            ->orWhereHas('member', function ($query) {
                $query->where('full_name', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->borrowDateFrom && $this->borrowDateTo) {
            $query->whereBetween('borrow_date', [$this->borrowDateFrom, $this->borrowDateTo]);
        }
    
        if ($this->returnDateFrom && $this->returnDateTo) {
            $query->whereBetween('return_date', [$this->returnDateFrom, $this->returnDateTo]);
        }
    
        if ($this->returnedDateFrom && $this->returnedDateTo) {
            $query->whereBetween('returned_date', [$this->returnedDateFrom, $this->returnedDateTo]);
        }

        switch ($this->sortBy) {
            case 'member-name-asc':
                $query->join('members', 'borrow_books.member_id', '=', 'members.id')
                      ->orderBy('members.full_name', 'asc');
                break;
            case 'member-name-desc':
                $query->join('members', 'borrow_books.member_id', '=', 'members.id')
                      ->orderBy('members.full_name', 'desc');
                break;
            case 'newest':
                $query->orderBy('borrow_books.created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('borrow_books.created_at', 'asc');
                break;
        }

        return $query->paginate($this->perPage);
    }
    public function render()
    {
        $borrowBooks = $this->fetchBorrowBooks();
        $optionPages = [10, 20, 40, 50, 100];
        $columns = ['','#','Borrow Number','Member Name','Borrow Date','Return Date','Returned_date','Status','Added or Edited','Actions'];
        $optionSorts = [
            'newest' => 'Newest',
            'oldest' => 'Oldest',
            'member-name-asc' => 'Member Name A-Z',
            'member-name-desc' => 'Member Name Z-A'
        ];
        return view('livewire.borrow-book.index',compact('borrowBooks','optionPages','optionSorts','columns'));
    }
}
