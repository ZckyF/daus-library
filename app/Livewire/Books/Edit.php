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
    public $searchSelectCategories = '';
    public $searchSelectBookshelves = '';

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
        $this->user = $book->user->username;
        
        $this->form->setBook($book);

        // $this->categories = BookCategory::select('id', 'category_name')->get();
        // $this->bookshelves = Bookshelf::select('id', 'bookshelf_number')->get();
        
        $this->selectedDropdownCategories = $book->bookCategories()->wherePivot('deleted_at', null)->pluck('id')->toArray();
        $this->selectedDropdownBookshelves = $book->bookshelves()->wherePivot('deleted_at', null)->pluck('id')->toArray();

        
        $this->selectCategories();
        $this->selectBookshelves();

    }

    public function selectCategories()
    {
        $this->categories = BookCategory::select('id', 'category_name')->get();
 
        $selectedCategoryNames = $this->categories->whereIn('id', $this->selectedDropdownCategories)
                                ->pluck('category_name')
                                ->toArray();

        $this->form->selectedCategoriesId = $this->selectedDropdownCategories;
        $this->form->selectedCategories = implode(', ', $selectedCategoryNames);

        $this->dispatch('closeModal');
    }

    public function selectBookshelves()
    {
        $this->bookshelves = Bookshelf::select('id', 'bookshelf_number')->get();
        
        $selectedBookshelvesNumber = $this->bookshelves->whereIn('id', $this->selectedDropdownBookshelves)
        ->pluck('bookshelf_number')
        ->toArray();
        $this->form->selectedBookshelvesId = $this->selectedDropdownBookshelves;
        $this->form->selectedBookshelves = implode(', ', $selectedBookshelvesNumber);

        $this->dispatch('closeModal');

    }

    public function updatedSearchSelectCategories()
    {
        $this->categories = BookCategory::where('category_name', 'like', '%' . $this->searchSelectCategories . '%')->get();
    }
    public function updatedSearchSelectBookshelves()
    {
        $this->bookshelves = Bookshelf::where('bookshelf_number', 'like', '%' . $this->searchSelectBookshelves . '%')->get();
    }

    public function save()
    {
        
        $this->form->update();
        return $this->redirectRoute('books');
    }

    public function delete()
    {
        $book = $this->form->book;
    
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
