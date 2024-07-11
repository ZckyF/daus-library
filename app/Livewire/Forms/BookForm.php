<?php

namespace App\Livewire\Forms;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BookForm extends Form
{

    public ?Book $book;

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
            'description' => 'required|string',
            'selectedCategories' => 'required|string',
            'selectedBookshelves' => 'required|string',
        ];
    }
    
    public function setBook(Book $book)
    {
        $this->book = $book;

        $this->isbn = $book->isbn;
        $this->title = $book->title;
        $this->cover_image_name = $book->cover_image_name;
        $this->author = $book->author;
        $this->published_year = $book->published_year;
        $this->price_per_book = $book->price_per_book;
        $this->quantity = $book->quantity;
        $this->quantity_now = $book->quantity_now;
        $this->description = $book->description;

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

        
        $this->reset();

        
        session()->flash('success', 'Book successfully created.');
    }


    public function update()
    {
        
        $book = $this->book;

        $rules = $this->rules();
        $rules['isbn'] .= '|'.Rule::unique('books', 'isbn')->ignore($book);
        if ($this->cover_image_name !== $book->cover_image_name) {
            $rules['cover_image_name'] .= '|image|mimes:jpeg,png,jpg,gif|max:2048';

            $fileName = $this->cover_image_name->hashName(); 
            $this->cover_image_name->storeAs('covers', $fileName, 'public');
        } else {
            $fileName = $book->cover_image_name;
        }


        $validatedData = $this->validate($rules);

        $book->update(array_merge($validatedData, [
            'cover_image_name' => $fileName,
            'user_id' => Auth::user()->id,
        ]));

        $this->syncCategoriesAndBookshelves($book);

        $this->reset();

        session()->flash('success', 'Book successfully updated.');
    }

    protected function syncCategoriesAndBookshelves(Book $book)
    {
        // Get the IDs of existing soft deleted categories and bookshelves
        $existingCategoryIds = $book->bookCategories()->wherePivot('deleted_at', '!=', null)->pluck('book_category_id')->toArray();
        $existingBookshelfIds = $book->bookshelves()->wherePivot('deleted_at', '!=', null)->pluck('bookshelf_id')->toArray();
    
        // Combine selected categories with existing soft deleted categories
        $categoryIdsToSync = array_unique(array_merge($this->selectedCategoriesId, $existingCategoryIds));
        $bookshelfIdsToSync = array_unique(array_merge($this->selectedBookshelvesId, $existingBookshelfIds));
    
        // Update the pivot table for categories
        foreach ($categoryIdsToSync as $categoryId) {
            $book->bookCategories()->updateExistingPivot($categoryId, ['deleted_at' => null]);
        }
    
        // Update the pivot table for bookshelves
        foreach ($bookshelfIdsToSync as $bookshelfId) {
            $book->bookshelves()->updateExistingPivot($bookshelfId, ['deleted_at' => null]);
        }
    
        // Sync the selected categories and bookshelves
        $book->bookCategories()->sync($this->selectedCategoriesId);
        $book->bookshelves()->sync($this->selectedBookshelvesId);
    }
    


    

    
}
