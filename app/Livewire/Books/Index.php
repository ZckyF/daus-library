<?php

namespace App\Livewire\Books;

use App\Models\Book;
use App\Models\BookCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $search = '';
    public $category = '';
    public $sortBy = 'newest';
    public $perPage = 10;
    public $bookId;
    public $showDeleteSelected = false;
    public $selectAllCheckbox = false;
    public $selectedBooks = [];
    
    

    public function fetchBooks()
    {
        
        $query = Book::query();

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('author', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->whereHas('bookCategories', function ($q) {
                $q->where('book_category_id', $this->category);
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

    public function updatedSearch() 
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

    public function toggleSelectAll()
    {
        if ($this->selectAllCheckbox) {
            $this->selectedBooks = Book::pluck('id')->toArray();
            $this->showDeleteSelected = true;
        } else {
            $this->selectedBooks = [];
            $this->showDeleteSelected = false;
        }
    }

    public function updatedSelectedBooks()
    {
        if ($this->selectedBooks) {
            $this->showDeleteSelected = true;
        } else {
            $this->showDeleteSelected = false;
        }
    }

    public function addToCart($bookId)
    {
        // Ambil data cart dari session, jika tidak ada maka buat array kosong
        $cart = session()->get('cart', []);
    
        // Hitung total buku dalam cart
        $totalBooksInCart = array_sum(array_column($cart, 'quantity'));
    
        // Cek jika sudah ada 3 buku dalam cart
        if ($totalBooksInCart >= 3) {
            session()->flash('error', 'You can only add up to 3 books to the cart.');
            return;
        }
    
        // Cek jika item sudah ada dalam cart
        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity'] += 1; // Tambah jumlah buku
        } else {
            // Tambahkan buku baru ke dalam cart
            $cart[$bookId] = [
                'quantity' => 1,
                // Data tambahan lain yang perlu disimpan bisa ditambahkan di sini
            ];
        }
    
        // Simpan cart yang telah diperbarui ke dalam session
        session()->put('cart', $cart);

        // Berikan pesan sukses ke pengguna
        session()->flash('success', 'Book added to cart.');
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
        $categories = BookCategory::all();
        $books = $this->fetchBooks();
        $optionPages = ['12','24','48','84','108'];
        return view('livewire.books.index', compact('books', 'categories', 'optionPages'));
    }
}
