<?php

namespace App\Livewire\BookCategories;

use App\Models\BookCategory;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Book Categories')]
class Index extends Component
{
    use WithPagination;

    /**
     * Search term for book categories.
     * 
     * @var string
     */
    public string $search = '';

    /**
     * Sorting criteria for book categories.
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
     * ID of the book category to be deleted.
     * 
     * @var int
     */
    public int $bookCategoryId;

    /**
     * Array of selected book category IDs.
     * 
     * @var array
     */
    public array $selectedBookCategories = [];

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
     * Update the showDeleteSelected flag when selected book categories are updated.
     * 
     * @return void
     */
    public function updatedSelectedBookCategories(): void
    {
        $this->showDeleteSelected = !empty($this->selectedBookCategories);
    }

    /**
     * Set the book category ID to be deleted.
     * 
     * @param int $bookCategoryId
     * @return void
     */
    public function setBookCategoryId(int $bookCategoryId): void
    {
        $this->bookCategoryId = $bookCategoryId;
    }

    /**
     * Delete a book category by its ID.
     * 
     * @return void
     */
    public function delete(): void
    {
       BookCategory::destroy($this->bookCategoryId);
       session()->flash('success', 'Book Category successfully deleted.');

       $this->dispatch('closeModal');
    }

    /**
     * Delete selected book categories.
     * 
     * @return void
     */
    public function deleteSelected(): void
    {
        BookCategory::whereIn('id', $this->selectedBookCategories)->delete();
        session()->flash('success', 'Selected Book Categories successfully deleted.');
        $this->selectedBookCategories = [];

        $this->showDeleteSelected = false;
        $this->dispatch('closeModal');
    }

    /**
     * Fetch book categories based on search, sorting, and pagination criteria.
     * 
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function fetchBookCategories(): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = BookCategory::query();

        if ($this->search) {
            $query->where('category_name', 'like', '%' . $this->search . '%');
        }

        switch ($this->sortBy) {
            case 'category-asc':
                $query->orderBy('category_name', 'asc');
                break;
            case 'category-desc':
                $query->orderBy('category_name', 'desc');
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
     * Render the component view.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $categories = $this->fetchBookCategories();
        $optionPages = [10, 20, 40, 50, 100];
        $columns = ['','#', 'Category Name', 'Added or Edited', 'Actions'];
        $optionSorts = [
            'newest' => 'Newest',
            'oldest' => 'Oldest',
            'category-asc' => 'Category A-Z',
            'category-desc' => 'Category Z-A'
        ];
        return view('livewire.book-categories.index', 
            compact('categories', 'optionPages', 'columns', 'optionSorts')
        );
    }
}

