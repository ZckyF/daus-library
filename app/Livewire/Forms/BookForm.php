<?php

namespace App\Livewire\Forms;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BookForm extends Form
{
    public $isbn;
    public $title;
    public $cover_image_name;
    public $author;
    public $published_year;
    public $price_per_book;
    public $quantity;
    public $quantity_now;
    public $description;
    public $selectedCategories;
    public $selectedBookshelves;

    public $selectedImage;

    public $selectedBookshelvesId;
    public $selectedCategoriesId;

    public function rules()
    {
        return [
            'isbn' => 'required|max:13',
            'title' => 'required|string|max:255',
            'cover_image_name' => 'required|max:2048',
            'author' => 'required|string|max:255',
            'published_year' => 'required|integer',
            'price_per_book' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'quantity_now' => 'required|integer|min:0',
            'description' => 'required|string|max:255',
            'selectedCategories' => 'required|string',
            'selectedBookshelves' => 'required|string',
        ];
    }
    

    public function store()
    {
        
        $rules = $this->rules();
        $rules['isbn'] .= '|unique:books,isbn';
        $rules['cover_image_name'] .= '|image|mimes:jpeg,png,jpg,gif';
        
        $validatedData = $this->validate($rules);

        $fileName = $this->cover_image_name->hashName(); 
        $this->cover_image_name->storeAs('covers', $fileName, 'public');
         
        $book = Book::create(array_merge($validatedData, [
            'cover_image_name' => $fileName,
            'user_id' => Auth::user()->id,
        ]));
 
        $book->bookCategories()->sync($this->selectedCategoriesId);
        $book->bookshelves()->sync($this->selectedBookshelvesId);

        
        $this->resetForm();

        
        session()->flash('success', 'Book successfully created.');
    }

    public function update($bookId)
    {
        
        $book = Book::findOrfail($bookId);
        $rules = $this->rules();
        if ($this->cover_image_name !== $book->cover_image_name) {
            $rules['cover_image_name'] .= '|image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['isbn'] .= '|'.Rule::unique('books', 'isbn')->ignore($book->id);
            
            $fileName = $this->cover_image_name->hashName(); 
            $this->cover_image_name->storeAs('covers', $fileName, 'public');
        } else {
            $rules['isbn'] .= '|'.Rule::unique('books', 'isbn')->ignore($book->id);
            $fileName = $book->cover_image_name;
        }
        $validatedData = $this->validate($rules);

        $book->update(array_merge($validatedData, [
            'cover_image_name' => $fileName,
            'user_id' => Auth::user()->id,
        ]));

        $book->bookCategories()->sync($this->selectedCategoriesId);
        $book->bookshelves()->sync($this->selectedBookshelvesId);

        $this->resetForm();

        session()->flash('success', 'Book successfully updated.');
    }



    protected function resetForm()
    {
        $this->reset('isbn', 'title', 'cover_image_name', 'author', 'published_year', 'price_per_book', 'quantity', 'quantity_now', 'description', 'selectedCategories', 'selectedBookshelves');
    }
    

    
}
