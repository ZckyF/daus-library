<?php

namespace App\Livewire\Books;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Bookshelf;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Books')]
class Index extends Component
{
    use WithPagination;
    public $search = '';
    public $searchCategory = '';
    public $searchBookshelves = '';
    public $categoryId;
    public $bookshelfId;
    public $sortBy = 'newest';
    public $perPage = 12;
    public $bookId;
    public $showDeleteSelected = false;
    public $selectedBooks = [];

    public $bookModalId;
    #[Rule('required|integer|min:1|max:3')]
    public $quantity = 1;
    
    

    public function fetchBooks()
    {
        
        $query = Book::query();

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('author', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryId) {
            $query->whereHas('bookCategories', function ($q) {
                $q->where('book_category_id', $this->categoryId);
            });
        }

        if ($this->bookshelfId) {
            $query->whereHas('bookshelves', function ($q) {
                $q->where('bookshelf_id', $this->bookshelfId);
            });
        }

        if ($this->sortBy == 'title-asc') {
            $query->orderBy('title', 'asc');
        } elseif ($this->sortBy == 'title-desc') {
            $query->orderBy('title', 'desc');
        } elseif ($this->sortBy == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($this->sortBy == 'oldest') {
            $query->orderBy('created_at', 'asc');
        }

        return $query->paginate($this->perPage);
    }

    public function fetchBookCategories()
    {
        $query = BookCategory::query();

        if ($this->searchCategory) {
            $query->where(function ($query) {
                $query->where('category_name', 'like', '%' . $this->searchCategory . '%');
            });
        }

        return $query->get();
    }

    public function fetchBookshelves()
    {
        $query = Bookshelf::query();
        if($this->searchBookshelves){
            $query->where('bookshelf_number', 'like', '%' . $this->searchBookshelves . '%');
        }

        return $query->get();
            
        
    }

    public function updatedSearch() 
    {
        $this->resetPage();
    }
    public function updatedSearchCategory() 
    {
        $this->resetPage();
    }
    public function updatedSearchBookshelves() 
    {
        $this->resetPage();
    }
    public function updatedCategory() 
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
    public function selectCategory($categoryId)
    {
        $this->categoryId = $categoryId;
        $this->resetPage();
    }
    public function selectBookshelf($bookshelfId)
    {
        $this->bookshelfId = $bookshelfId;
        $this->resetPage();
    }
    public function setBookId($bookId)
    {
        $this->bookId = $bookId;
    }
    public function delete()
    {
       Book::destroy($this->bookId);
       session()->flash('success', 'Book successfully deleted.');

       $this->dispatch('closeModal');
    }

    public function deleteSelected()
    {
        Book::destroy($this->selectedBooks);
        $this->selectedBooks = [];
        session()->flash('success', 'Books successfully deleted.');
        $this->dispatch('closeModal');
    }
    
    public function toggleSelectBook($bookId)
    {
        if (in_array($bookId, $this->selectedBooks)) {
            $key = array_search($bookId, $this->selectedBooks);
            unset($this->selectedBooks[$key]);
        } else {
            $this->selectedBooks[] = $bookId;
        }
        $this->showDeleteSelected = !empty($this->selectedBooks);
    }

    public function setBookModalId($bookId)
    {
        $this->bookModalId = $bookId;
        $this->quantity = 1;
    }

    public function addToCart()
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

    


    public function render()
    {
        $categories = $this->fetchBookCategories();
        $bookshelves = $this->fetchBookshelves();
        $books = $this->fetchBooks();
        $optionPages = ['12','24','48','84','108'];
        $optionSorts = [
            'newest' => 'Newest',
            'oldest' => 'Oldest',
            'title-asc' => 'Title A-Z',
            'title-desc' => 'Title Z-A',
        ];

        return view('livewire.books.index', compact('books', 'categories','bookshelves', 'optionPages', 'optionSorts'));
    }
}
