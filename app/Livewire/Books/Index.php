<?php

namespace App\Livewire\Books;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Bookshelf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Books')]
class Index extends Component
{
    use WithPagination;

    /**
     * The search term input for books.
     * 
     * @var string
     */
    public string $search = '';

    /**
     * The search term input for book categories.
     * 
     * @var string
     */
    public string $searchBookCategory = '';

    /**
     * The search term input for bookshelves.
     * 
     * @var string
     */
    public string $searchBookshelves = '';

    /**
     * The category ID for filtering books.
     * 
     * @var int|null
     */
    public ?int $bookCategoryId = null;

    /**
     * The bookshelf ID for filtering books.
     * 
     * @var int|null
     */
    public ?int $bookshelfId = null;

    /**
     * The sorting criteria for the books display.
     * Default is 'newest'.
     * 
     * @var string
     */
    public string $sortBy = 'newest';

    /**
     * The number of items per page to display.
     * Default is 12.
     * 
     * @var int
     */
    public int $perPage = 12;

    /**
     * The ID of the book.
     * 
     * @var int
     */
    public int $bookId;

    /**
     * Flag to show delete selected books option.
     * Default is false.
     * 
     * @var bool
     */
    public bool $showDeleteSelected = false;

    /**
     * Array of selected books.
     * 
     * @var array
     */
    public array $selectedBooks = [];

    /**
     * The ID of the book for the modal.
     * 
     * @var int
     */
    public int $bookModalId;

    /**
     * The quantity of the book selected to add to the cart.
     * Must be an integer between 1 and 3.
     * 
     * @var int
     */
    #[Rule('required|integer|min:1|max:3')]
    public $quantity = 1;
    
    
     /**
     * Fetches books based on search criteria and filters.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator; 
     */
    public function fetchBooks(): \Illuminate\Pagination\LengthAwarePaginator
    {
        
        $query = Book::query();

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('author', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->bookCategoryId) {
            $query->whereHas('bookCategories', function ($q) {
                $q->where('book_category_id', $this->bookCategoryId)
                  ->whereNull('book_category_pivot.deleted_at');
            });
        }
        
        if ($this->bookshelfId) {
            $query->whereHas('bookshelves', function ($q) {
                $q->where('bookshelf_id', $this->bookshelfId)
                  ->whereNull('bookshelf_pivot.deleted_at');
            });
        }
        

        switch ($this->sortBy) {
            case 'title-asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title-desc':
                $query->orderBy('title', 'desc');
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
     * Fetches book categories based on search criteria.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetchBookCategories(): \Illuminate\Database\Eloquent\Collection
    {
        $query = BookCategory::query();

        if ($this->searchBookCategory) {
            $query->where(function ($query) {
                $query->where('category_name', 'like', '%' . $this->searchBookCategory . '%');
            });
        }
        return $query->get();
    }

    /**
     * Fetches bookshelves based on search criteria.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function fetchBookshelves(): \Illuminate\Database\Eloquent\Collection
    {
        $query = Bookshelf::query();
        if($this->searchBookshelves){
            $query->where('bookshelf_number', 'like', '%' . $this->searchBookshelves . '%');
        }

        return $query->get();
            
        
    }
    /**
     * Resets the page when the search term is updated.
     * 
     * @return void
     */
    public function updatedSearch(): void 
    {
        $this->resetPage();
    }
    /**
     * Updates the page when the book category is updated.
     * 
     * @return void
     */
    public function updatedSearchBookCategory(): void
    {
        $this->resetPage();
    }
    /**
     * Updates the page when the bookshelves is updated.
     * 
     * @return void
     */
    public function updatedSearchBookshelves(): void
    {
        $this->resetPage();
    }
    /**
     * Updates the page when the category is updated.
     * 
     * @return void
     */
    public function updatedCategory(): void 
    {
        $this->resetPage();
    }
    /**
     * Updates the page when the sort by is updated.
     * 
     * @return void
     */
    public function updatedSortBy(): void 
    {
        $this->resetPage();
    }
    /**
     * Updates the page when the per page is updated.
     * 
     * @return void
     */
    public function updatedPerPage(): void 
    {
        $this->resetPage();
    }
    /**
     * Selects the book category to filter books by.
     * 
     * @param int $bookCategoryId 
     * @return void
     */
    public function selectBookCategory(int $bookCategoryId): void 
    {
        $this->bookCategoryId = $bookCategoryId;
        $this->resetPage();
    }
    /**
     * Selects the bookshelf to filter books by.
     * 
     * @param int $bookshelfId
     * @return void
     */
    public function selectBookshelf(int $bookshelfId): void 
    {
        $this->bookshelfId = $bookshelfId;
        $this->resetPage();
    }
    /**
     * Sets the book ID for the modal.
     * 
     * @param int $bookId
     * @return void
     */
    public function setBookId(int $bookId): void 
    {
        $this->bookId = $bookId;
    }
    /**
     * Deletes a book by ID.
     * 
     * @return void
     */
    public function delete(): void 
    {
        
        $book = Book::find($this->bookId);
        if (Gate::denies('delete', $book)) {
            abort(403);
        }
        $book->delete();
        $book->update(['user_id' => Auth::user()->id]);  
        session()->flash('success', 'Book successfully deleted');
    
        
        $this->dispatch('closeModal');
    }

    /**
     * Deletes selected books.
     * 
     * @return void
     */
    public function deleteSelected(): void 
    {
        $books = Book::find($this->selectedBooks);
        if (Gate::denies('delete', $books[0])) {
            abort(403);
        }
        foreach ($books as $book) {
            $book->delete();
            $book->update(['user_id' => Auth::user()->id]);
        }

        $this->selectedBooks = [];
        session()->flash('success', 'Books successfully deleted');
        $this->dispatch('closeModal');
    }

    /**
     * Updates the visibility of the delete selected button based on selected books.
     * 
     * @return void
     * 
     * */
    public function updatedSelectedBooks(): void 
    {
        $this->showDeleteSelected = !empty($this->selectedBooks);
    }
    /**
     * Sets the book ID for the modal.
     * 
     * @param int $bookId
     * @return void
     */
    public function setBookModalId(int $bookId): void 
    {
        $this->bookModalId = $bookId;
        $this->quantity = 1;
    }
    /**
     * Adds a book to the cart.
     * 
     * @return void
     */
    public function addToCart(): void 
    {
        $this->validate();
    
        $cart = session()->get('cart', []);
    
        $totalBooks = array_sum(array_column($cart, 'quantity'));
    
        if ($totalBooks + $this->quantity > 3) {
            session()->flash('error', 'You can only add up to 3 books to the cart.');
            $this->dispatch('closeModal');
            return;
        }
    
        if (isset($cart[$this->bookModalId])) {
            $cart[$this->bookModalId]['quantity'] += $this->quantity;
        } else {
            $cart[$this->bookModalId] = [
                'quantity' => $this->quantity,
            ];
        }
    
        session()->put('cart', $cart);
        session()->flash('success', 'Book added to cart successfully.');
    
        $this->dispatch('closeModal');
    }
    
    // public function showCart()
    // {
    //     // Ambil data cart dari session
    //     $cart = session()->get('cart', []);

    //     // Ambil detail produk dari database berdasarkan ID di cart
    //     return Book::whereIn('id', array_keys($cart))->get();

    // }

    

    /**
     * Render the livewire component.
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        $bookCategories = $this->fetchBookCategories();
        $bookshelves = $this->fetchBookshelves();
        $books = $this->fetchBooks();
        $optionPages = [12,24,48,84,108];
        $optionSorts = [
            'newest' => 'Newest',
            'oldest' => 'Oldest',
            'title-asc' => 'Title A-Z',
            'title-desc' => 'Title Z-A',
        ];

        return view('livewire.books.index', compact('books', 'bookCategories','bookshelves', 'optionPages', 'optionSorts'));
    }
}
