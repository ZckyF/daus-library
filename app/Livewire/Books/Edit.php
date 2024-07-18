<?php

namespace App\Livewire\Books;

use App\Livewire\Forms\BookForm;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Bookshelf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
#[Title('Edit Book')]
class Edit extends Component
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
     * Array of selected category IDs for modal.
     * 
     * @var array
     */
    public array $selectedModalCategories = [];

    /**
     * Array of selected bookshelf IDs for modal.
     * 
     * @var array
     */
    public array $selectedModalBookshelves = [];
    
    /**
     * Form instance for editing book.
     * 
     * @var BookForm
     */
    public BookForm $form;

    /**
     * Initialize the component with default values and fetch the book details.
     * 
     * @param string $title
     * @param string $author
     * @return void
     */
    public function mount(string $title, string $author): void
    {
        $title = str_replace('-', ' ', $title);
        $author = str_replace('-', ' ', $author);

        $book = Book::where('title', $title)->where('author', $author)->firstOrFail();

        if (!$book) {
            abort(404);
        }
        if (Gate::denies('view', $book)) {
            abort(403,'This action is unauthorized.');
        }

        $this->user = $book->user->username;
        $this->form->setBook($book);

        $this->selectedModalCategories = $book->bookCategories()
            ->wherePivot('deleted_at', null)
            ->pluck('id')
            ->toArray();

        $this->selectedModalBookshelves = $book->bookshelves()
            ->wherePivot('deleted_at', null)
            ->pluck('id')
            ->toArray();

        $this->selectCategories();
        $this->selectBookshelves();
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
     * Update the category list based on the search term.
     * 
     * @return void
     */
    public function updatedSearchSelectCategories(): void
    {
        $this->categories = BookCategory::where('category_name', 'like', '%' . $this->searchSelectCategories . '%')->get();
    }

    /**
     * Update the bookshelf list based on the search term.
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
        if (Gate::denies('create', Book::class)) {
            abort(403,'This action is unauthorized.');
        }
        $this->form->update();
        $this->redirectRoute('books');
    }

    /**
     * Delete the book and its cover image if it exists.
     * 
     * @return void
     */
    public function delete(): void
    {
        $book = $this->form->book;
        if (Gate::denies('update', $book)) {
            abort(403,'This action is unauthorized.');
        }
        if ($book) {
            $coverImage = $book->cover_image_name;

            $book->delete();
            $book->update(['user_id' => Auth::user()->id]);

            if ($coverImage && $coverImage !== 'default.jpg') {
                Storage::disk('public')->delete('covers/' . $coverImage);
            }

            session()->flash('success', 'Book deleted successfully');

            $this->redirectRoute('books');
        } else {
            session()->flash('error', 'Book not found');
        }
    }

    /**
     * Render the component view.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $isEditPage = true;
        return view('livewire.books.edit', compact('isEditPage'));
    }
}

