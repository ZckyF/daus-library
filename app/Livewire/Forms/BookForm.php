<?php

namespace App\Livewire\Forms;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BookForm extends Form
{
    #[Rule('required|max:13')]
    public $isbn;
    #[Rule('required|string|max:255')]
    public $title;
    // #[Rule('nullable|image|mimes:jpeg,png,jpg,gif|max:2048')]
    public $cover_image_name;
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
    public $selectedCategories;
    #[Rule('required|string')]
    public $selectedBookshelves;

    public $selectedImage;

    public $selectedBookshelvesId;
    public $selectedCategoriesId;
    

    public function store()
    {
        

        if ($this->cover_image_name) {
            $validatedData = array_merge($this->validate(), ['cover_image_name' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048']);
            $fileName = $this->cover_image_name->hashName(); 
            $this->cover_image_name->storeAs('covers', $fileName, 'public');
        } 
        $book = Book::create(array_merge($validatedData, [
            'cover_image_name' => $fileName,
            'user_id' => Auth::user()->id,
        ]));
 
        $book->bookCategories()->sync($this->selectedCategoriesId);
        $book->bookshelves()->sync($this->selectedBookshelvesId);

        
        $this->resetForm();

        
        session()->flash('success', 'Book successfully created.');
    }

    public function update()
    {
        
        $book = Book::where('isbn', $this->isbn)->firstOrFail();

        if ($this->cover_image_name !== $book->cover_image_name) {
            $validatedData = array_merge($this->validate(), ['cover_image_name' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048']);
            
            $fileName = $this->cover_image_name->hashName(); 
            $this->cover_image_name->storeAs('covers', $fileName, 'public');
        } else {
            $validatedData = array_merge($this->validate(), ['cover_image_name' => 'required|max:2048']);
            $fileName = $book->cover_image_name;
        }

        $book->update(array_merge($validatedData, [
            'cover_image_name' => $fileName,
            'user_id' => Auth::user()->id,
        ]));

        $book->bookCategories()->sync($this->selectedCategoriesId);
        $book->bookshelves()->sync($this->selectedBookshelvesId);

        $this->resetForm();

        session()->flash('success', 'Book successfully updated.');
    }



    public function resetForm()
    {

        $this->reset('isbn', 'title', 'cover_image_name', 'author', 'published_year', 'price_per_book', 'quantity', 'quantity_now', 'description', 'selectedCategories', 'selectedBookshelves');
    }
    

    
}
