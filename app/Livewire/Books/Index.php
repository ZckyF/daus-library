<?php

namespace App\Livewire\Books;

use App\Models\Book;
use App\Models\BookCategory;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $category = '';
    public $sortBy = 'newest';
    public $categories;
    public $books;
    
    

    public function mount()
    {
        $this->categories = BookCategory::all();
        $this->fetchBooks();
    }

    public function fetchBooks()
    {
        
        $query = Book::query();

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
            ->orWhere('author', 'like', '%' . $this->search . '%');
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
            $query->orderBy('published_year', 'desc');
        } elseif ($this->sortBy == 'oldest') {
            $query->orderBy('published_year', 'asc');
        }

        $this->books = $query->get();
    }

    public function updatedSearch() 
    {
        $this->fetchBooks();
    }
    public function updatedCategory() 
    {
        $this->fetchBooks();
    }
    public function updatedSortBy() 
    {
        $this->fetchBooks();
    }

    public function render()
    {
        return view('livewire.books.index');
    }
}
