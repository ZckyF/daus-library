<?php

namespace App\Livewire\Bookshelves;

use App\Models\Bookshelf;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Bookshelves')]
class Index extends Component
{
    use WithPagination;

    /**
     * Search term for bookshelves.
     * 
     * @var string
     */
    public string $search = '';

    /**
     * Sort order.
     * 
     * @var string
     */
    public string $sortBy = 'newest';

    /**
     * Number of items per page.
     * 
     * @var int
     */
    public int $perPage = 10;

    /**
     * ID of the selected bookshelf.
     * 
     * @var int
     */
    public int $bookshelfId;

    /**
     * List of selected bookshelves.
     * 
     * @var array
     */
    public array $selectedBookshelves = [];

    /**
     * Flag to show or hide the delete selected button.
     * 
     * @var bool
     */
    public bool $showDeleteSelected = false;

    /**
     * Reset pagination on search update.
     * 
     * @return void
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination on sort order update.
     * 
     * @return void
     */
    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination on per page update.
     * 
     * @return void
     */
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Toggle the show delete selected button.
     * 
     * @return void
     */
    public function updatedSelectedBookshelves(): void
    {
        $this->showDeleteSelected = !empty($this->selectedBookshelves);
    }

    /**
     * Set the bookshelf ID.
     * 
     * @param int $bookshelfId
     * @return void
     */
    public function setBookShelfId($bookshelfId): void
    {
        $this->bookshelfId = $bookshelfId;
    }

    /**
     * Delete a bookshelf by its ID.
     * 
     * @return void
     */
    public function delete(): void
    {
        Bookshelf::destroy($this->bookshelfId);
        session()->flash('success', 'Bookshelf successfully deleted.');
        $this->dispatch('closeModal');
    }

    /**
     * Delete selected bookshelves.
     * 
     * @return void
     */
    public function deleteSelected(): void
    {
        Bookshelf::whereIn('id', $this->selectedBookshelves)->delete();
        session()->flash('success', 'Selected bookshelves successfully deleted.');
        $this->selectedBookshelves = [];
        $this->showDeleteSelected = false;
        $this->dispatch('closeModal');
    }

    /**
     * Fetch bookshelves based on search and sort criteria.
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function fetchBookCategories()
    {
        $query = Bookshelf::query();

        if ($this->search) {
            $query->where('bookshelf_number', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
        }

        switch ($this->sortBy) {
            case 'bookshelf-asc':
                $query->orderBy('bookshelf_number', 'asc');
                break;
            case 'bookshelf-desc':
                $query->orderBy('bookshelf_number', 'desc');
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
     * Render the component.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $bookshelves = $this->fetchBookCategories();
        $optionPages = [10, 20, 40, 50, 100];
        $columns = ['','#','Bookshelf Number','Added or Edited','Actions'];
        $optionSorts = [
            'newest' => 'Newest',
            'oldest' => 'Oldest',
            'bookshelf-asc' => 'Bookshelf A-Z',
            'bookshelf-desc' => 'Bookshelf Z-A'
        ];
        return view('livewire.bookshelves.index', compact('bookshelves', 'optionPages', 'columns', 'optionSorts'));
    }
}
