<?php

namespace App\Livewire\Books;

use App\Models\BookCategory;
use App\Models\Bookshelf;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;
    public $isbn;
    public $title;
    public $cover_image;
    public $author;
    public $published_year;
    public $price_per_book;
    public $quantity;
    public $quantity_now;
    public $description;
    public $category_id;

    public $categories;
    public $bookshelves;

    public $selectedDropdownCategories = [];
    public $selectedDropdownBookshelves = [];
    public $selectedCategories = '';
    public $selectedBookshelves = '';
    public $selectedImage;

    public function mount() 
    {
        $this->categories = BookCategory::select('id', 'category_name')->get();
        $this->bookshelves = Bookshelf::select('id', 'bookshelf_number')->get();
    }

    public function updatedSelectedDropdownCategories()
    {
        $selectedCategoryNames = $this->categories->whereIn('id', $this->selectedDropdownCategories)
                                                  ->pluck('category_name')
                                                  ->toArray();
    
        $this->selectedCategories = implode(', ', $selectedCategoryNames);
    }
    public function updatedSelectedDropdownBookshelves()
    {
        $selectedBookshelvesNumber = $this->bookshelves->whereIn('id', $this->selectedDropdownBookshelves)
                                                  ->pluck('bookshelf_number')
                                                  ->toArray();
    
        $this->selectedBookshelves = implode(', ', $selectedBookshelvesNumber);
    }
    


    public function render()
    {
        return view('livewire.books.create');
    }
}
