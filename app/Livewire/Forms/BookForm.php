<?php

namespace App\Livewire\Forms;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BookForm extends Form
{
    /**
     * Optional existing book model instance.
     * 
     * @var Book|null
     */
    public ?Book $book = null;

    /**
     * ISBN of the book.
     * 
     * @var string
     */
    public $isbn;

    /**
     * Title of the book.
     * 
     * @var string
     */
    public $title;

    /**
     * Name of the cover image.
     * 
     * @var mixed
     */
    public $cover_image_name;

    /**
     * Author of the book.
     * 
     * @var string
     */
    public $author;

    /**
     * Published year of the book.
     * 
     * @var int
     */
    public $published_year;

    /**
     * Price per book.
     * 
     * @var float
     */
    public $price_per_book;

    /**
     * Total quantity of books.
     * 
     * @var int
     */
    public $quantity;

    /**
     * Current available quantity of books.
     * 
     * @var int
     */
    public $quantity_now;

    /**
     * Description of the book.
     * 
     * @var string
     */
    public $description;

    /**
     * Selected categories for the book.
     * 
     * @var array
     */
    public $selectedCategories;

    /**
     * Selected bookshelves for the book.
     * 
     * @var array
     */
    public $selectedBookshelves;

    /**
     * Selected image for the cover.
     * 
     * @var mixed
     */
    public $selectedImage;

    /**
     * IDs of selected categories.
     * 
     * @var array
     */
    public $selectedCategoriesId;

    /**
     * IDs of selected bookshelves.
     * 
     * @var array
     */
    public $selectedBookshelvesId;

    /**
     * Validation rules for book creation/updation.
     * 
     * @return array
     */
    public function rules(): array
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

    /**
     * Validation messages for book creation/updation.
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'cover_image_name.required' => 'Cover is required.',
            'cover_image_name.max' => 'Cover is too large, max 2MB.',
            'cover_image_name.image' => 'Cover must be an image.',
            'cover_image_name.mimes' => 'Cover must be jpeg, png, jpg, gif.',
        ];
    }

    /**
     * Set the book instance for editing.
     * 
     * @param Book $book
     * @return void
     */
    public function setBook(Book $book): void
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

    
        $this->selectedCategoriesId = $book->bookCategories->pluck('id')->toArray();
        $this->selectedBookshelvesId = $book->bookshelves->pluck('id')->toArray();
    }

    /**
     * Store a new book record.
     * 
     * @return void
     */
    public function store(): void
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

        $this->syncCategoriesAndBookshelves($book);

        $this->reset();

        session()->flash('success', 'Book successfully created.');
    }

    /**
     * Update an existing book record.
     * 
     * @return void
     */
    public function update(): void
    {
        $book = $this->book;

        $rules = $this->rules();
        $rules['isbn'] .= '|'.Rule::unique('books', 'isbn')->ignore($book);
        
        if ($this->cover_image_name !== $book->cover_image_name) {
            $rules['cover_image_name'] .= '|image|mimes:jpeg,png,jpg,gif';

            $fileName = $this->cover_image_name->hashName(); 
            $this->cover_image_name->storeAs('covers', $fileName, 'public');

            if ($book->cover_image_name && $book->cover_image_name !== 'default.jpg') {
                Storage::disk('public')->delete('covers/' . $book->cover_image_name);
            }
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

    /**
     * Sync selected categories and bookshelves for the book.
     * 
     * @param Book $book
     * @return void
     */
    protected function syncCategoriesAndBookshelves(Book $book): void
    {
        
        $existingCategoryIds = $book->bookCategories()->wherePivot('deleted_at', '!=', null)->pluck('book_category_id')->toArray();
        $existingBookshelfIds = $book->bookshelves()->wherePivot('deleted_at', '!=', null)->pluck('bookshelf_id')->toArray();
    
        
        $categoryIdsToSync = array_unique(array_merge($this->selectedCategoriesId, $existingCategoryIds));
        $bookshelfIdsToSync = array_unique(array_merge($this->selectedBookshelvesId, $existingBookshelfIds));
    
        
        foreach ($categoryIdsToSync as $categoryId) {
            $book->bookCategories()->updateExistingPivot($categoryId, ['deleted_at' => null]);
        }
    
        
        foreach ($bookshelfIdsToSync as $bookshelfId) {
            $book->bookshelves()->updateExistingPivot($bookshelfId, ['deleted_at' => null]);
        }
    
        
        $book->bookCategories()->sync($this->selectedCategoriesId);
        $book->bookshelves()->sync($this->selectedBookshelvesId);
    }
}

