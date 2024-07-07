<?php

namespace App\Livewire\Books;

use App\Livewire\Forms\BookForm;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Bookshelf;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Edit Book')]
class Edit extends Component
{

    use WithFileUploads;

    public $categories;
    public $bookshelves;
    public $bookId;
    public $user;

    public $selectedDropdownCategories = [];
    public $selectedDropdownBookshelves = [];
    
    public BookForm $form;

    public function mount($title, $author)
    {
    
        $title = str_replace('-', ' ', $title);
        $author = str_replace('-', ' ', $author);

        $book = Book::where('title', $title)->where('author', $author)->firstOrFail();

        if (!$book) {
            abort(404);
        }
        $this->bookId = $book->id;
        $this->user = $book->user->username;
        
        $this->form->isbn = $book->isbn;
        $this->form->title = $book->title;
        $this->form->cover_image_name = $book->cover_image_name;
        $this->form->author = $book->author; 
        $this->form->published_year = $book->published_year;
        $this->form->price_per_book = $book->price_per_book;
        $this->form->quantity = $book->quantity;
        $this->form->quantity_now = $book->quantity_now;
        $this->form->description = $book->description;

        $this->categories = BookCategory::select('id', 'category_name')->get();
        $this->bookshelves = Bookshelf::select('id', 'bookshelf_number')->get();
        
        $this->selectedDropdownCategories = $book->bookCategories->pluck('id')->toArray();
        $this->selectedDropdownBookshelves = $book->bookshelves->pluck('id')->toArray();

        $this->updatedSelectedDropdownCategories();
        $this->updatedSelectedDropdownBookshelves();

    }

    public function updatedSelectedDropdownCategories()
    {
        $selectedCategoryNames = $this->categories->whereIn('id', $this->selectedDropdownCategories)
                                ->pluck('category_name')
                                ->toArray();

        $this->form->selectedCategoriesId = $this->selectedDropdownCategories;
        $this->form->selectedCategories = implode(', ', $selectedCategoryNames);
    }

    public function updatedSelectedDropdownBookshelves()
    {
        $selectedBookshelvesNumber = $this->bookshelves->whereIn('id', $this->selectedDropdownBookshelves)
        ->pluck('bookshelf_number')
        ->toArray();
        $this->form->selectedBookshelvesId = $this->selectedDropdownBookshelves;
        $this->form->selectedBookshelves = implode(', ', $selectedBookshelvesNumber);

    }

    // public function updatedForm()
    // {
    //     $this->validateOnly('form');

    //     // Check if form data has changed from original
    //     $this->isDirty = $this->book->isbn !== $this->bookData['isbn'] ||
    //                $this->book->title !== $this->bookData['title'] ||
    //                // Add checks for other fields as needed
    //                $this->book->description !== $this->bookData['description'];

    //     // Disable save button if no changes or reverted to original
    //     if (!$this->isDirty) {
    //         $this->dispatchBrowserEvent('disable-save-button');
    //     }
    // }

    public function save()
    {
        
        $this->form->update($this->bookId);
        $this->redirectRoute('books');
    }

    public function delete()
    {
        $book = Book::find($this->bookId);
    
        if ($book) {
            $coverImage = $book->cover_image_name;
    
            $book->delete();
    
        
            if ($coverImage && $coverImage !== 'default.jpg') {
                Storage::disk('public')->delete('covers/' . $coverImage);
            }
    
            session()->flash('success', 'Book deleted successfully');
            
            $this->redirectRoute('books');
        } else {
            session()->flash('error', 'Book not found');
        }
    }
    
    public function render()
    {
        $isEditPage = true;
        return view('livewire.books.edit',compact('isEditPage'));
    }
}
