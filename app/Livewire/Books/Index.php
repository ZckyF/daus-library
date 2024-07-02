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

    public function render()
    {
        $categories = BookCategory::all();
        $books = $this->fetchBooks();
        $optionPages = ['12','24','48','84','108'];
        return view('livewire.books.index', compact('books', 'categories', 'optionPages'));
    }
}
