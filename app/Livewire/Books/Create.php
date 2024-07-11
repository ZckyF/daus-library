<?php

namespace App\Livewire\Books;

use App\Livewire\Forms\BookForm;
use App\Models\BookCategory;
use App\Models\Bookshelf;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Create Book')]
class Create extends Component
{
    use WithFileUploads;

    public $categories;
    public $bookshelves;
    public $searchSelectCategories = '';
    public $searchSelectBookshelves = '';

    public $selectedDropdownCategories = [];
    public $selectedDropdownBookshelves = [];
    
    public BookForm $form;

    public function mount() 
    {
        $this->categories = BookCategory::select('id', 'category_name')->get();
        $this->bookshelves = Bookshelf::select('id', 'bookshelf_number')->get();
        
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
        $this->form->store();
        return $this->redirectRoute('books'); 
    }



    public function render()
    {
        return view('livewire.books.create');
    }
}
