<?php

namespace App\Livewire\Books;

use App\Livewire\Forms\BookForm;
use App\Models\BookCategory;
use App\Models\Bookshelf;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Create Book')]
class Create extends Component
{
    use WithFileUploads;

    /**
     * Collection of categories.
     * 
     * @var Collection
     */
    public Collection $categories;

    /**
     * Collection of bookshelves.
     * 
     * @var Collection
     */
    public Collection $bookshelves;

    /**
     * Search term for selecting categories.
     * 
     * @var string
     */
    public string $searchSelectCategories = '';

    /**
     * Search term for selecting bookshelves.
     * 
     * @var string
     */
    public string $searchSelectBookshelves = '';

    /**
     * Username of the user who created or edited the book category.
     * 
     * @var string
     */
    public string $user;

    /**
     * Array of selected category IDs from modal.
     * 
     * @var array
     */
    public array $selectedModalCategories = [];

    /**
     * Array of selected bookshelf IDs from modal.
     * 
     * @var array
     */
    public array $selectedModalBookshelves = [];

    /**
     * Form instance for creating book.
     * 
     * @var BookForm
     */
    public BookForm $form;

    /**
     * Initialize component with categories and bookshelves data.
     * 
     * @return void
     */
    public function mount(): void
    {
        $this->categories = BookCategory::select('id', 'category_name')->get();
        $this->bookshelves = Bookshelf::select('id', 'bookshelf_number')->get();
    }

    /**
     * Select and set categories for the book form.
     * 
     * @return void
     */
    public function selectCategories(): void
    {
        $this->categories = BookCategory::select('id', 'category_name')->get();
        $selectedCategoryNames = $this->categories->whereIn('id', $this->selectedModalCategories)
            ->pluck('category_name')
            ->toArray();

        $this->form->selectedCategoriesId = $this->selectedModalCategories;
        $this->form->selectedCategories = implode(', ', $selectedCategoryNames);

        $this->dispatch('closeModal');
    }

    /**
     * Select and set bookshelves for the book form.
     * 
     * @return void
     */
    public function selectBookshelves(): void
    {
        $this->bookshelves = Bookshelf::select('id', 'bookshelf_number')->get();
        $selectedBookshelvesNumber = $this->bookshelves->whereIn('id', $this->selectedModalBookshelves)
            ->pluck('bookshelf_number')
            ->toArray();

        $this->form->selectedBookshelvesId = $this->selectedModalBookshelves;
        $this->form->selectedBookshelves = implode(', ', $selectedBookshelvesNumber);

        $this->dispatch('closeModal');
    }

    /**
     * Update categories list based on search term.
     * 
     * @return void
     */
    public function updatedSearchSelectCategories(): void
    {
        $this->categories = BookCategory::where('category_name', 'like', '%' . $this->searchSelectCategories . '%')->get();
    }

    /**
     * Update bookshelves list based on search term.
     * 
     * @return void
     */
    public function updatedSearchSelectBookshelves(): void
    {
        $this->bookshelves = Bookshelf::where('bookshelf_number', 'like', '%' . $this->searchSelectBookshelves . '%')->get();
    }

    /**
     * Save the book form data.
     * 
     * @return void
     */
    public function save(): void
    {
        $this->form->store();
        $this->redirectRoute('books'); 
    }

    /**
     * Render the component view.
     * 
     * @return View
     */
    public function render()
    {
        return view('livewire.books.create');
    }
}

