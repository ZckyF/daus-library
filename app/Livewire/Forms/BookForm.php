<?php

namespace App\Livewire\Forms;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BookForm extends Form
{
    #[Rule('required|max:13|unique:books,isbn')]
    public $isbn;
    #[Rule('required|string|max:255')]
    public $title;
    #[Rule('required|image|mimes:jpeg,png,jpg,gif|max:2048')]
    public $cover_image;
    #[Rule('required|string|max:255')]
    public $author;
    #[Rule('required|integer')]
    public $published_year;
    #[Rule('required|numeric|min:0')]
    public $price_per_book;
    #[Rule('required|integer|min:0')]
    public $quantity;
    #[Rule('required|integer|min:0')]
    public $quantity_now;
    #[Rule('required|string')]
    public $description;
    #[Rule('required|string')]
    public $selectedCategories ;
    #[Rule('required|string')]
    public $selectedBookshelves ;


    public $selectedBookshelvesId;
    public $selectedCategoriesId;
    
    public function store()
    {
        // Validate the form data
        $this->validate();

        if ($this->cover_image) {
            $fileName = $this->cover_image->hashName(); 
            $this->cover_image->storeAs('covers', $fileName, 'public');
        } else {
            $fileName = 'default.jpg'; 
        }

        // Create the book record
        $book = Book::create([
            'isbn' => $this->isbn,
            'title' => $this->title,
            'cover_image_name' => $fileName,
            'author' => $this->author,
            'published_year' => $this->published_year,
            'price_per_book' => $this->price_per_book,
            'quantity' => $this->quantity,
            'quantity_now' => $this->quantity_now,
            'description' => $this->description,
            'user_id' => Auth::user()->id,
        ]);
 
        $book->bookCategories()->sync($this->selectedCategoriesId);
        $book->bookshelves()->sync($this->selectedBookshelvesId);

        
        $this->resetForm();

        
        session()->flash('success', 'Book successfully created.');
        return redirect()->to('/books');
    }

    public function resetForm()
    {
        $this->isbn = '';
        $this->title = '';
        $this->cover_image = null;
        $this->author = '';
        $this->published_year = '';
        $this->price_per_book = '';
        $this->quantity = '';
        $this->quantity_now = '';
        $this->description = '';
        $this->selectedCategories = [];
        $this->selectedBookshelves = [];
    }
    

    
}
