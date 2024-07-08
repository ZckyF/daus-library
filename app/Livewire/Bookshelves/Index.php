<?php

namespace App\Livewire\Bookshelves;

use App\Models\Bookshelf;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Bookshelves')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'newest';
    public $perPage = 10;
    public $bookshelfId;
    public $selectedBookshelves = [];
    public $showDeleteSelected = false;
    public $selectAllCheckbox = false;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSelectedBookshelves()
    {
        
        if(count($this->selectedBookshelves) > 0) {
            $this->showDeleteSelected = true;
        } else {
            $this->showDeleteSelected = false;
        }
       
    }
    public function setBookShelfId($bookshelfId)
    {
        $this->bookshelfId = $bookshelfId;
    }
    public function delete()
    {
       Bookshelf::destroy($this->bookshelfId);
       session()->flash('success', 'Bookshelf successfully deleted.');

       $this->dispatch('closeModal');
    }
    public function toggleSelectAll()
    {
        if ($this->selectAllCheckbox) {
            $this->selectedBookshelves = Bookshelf::pluck('id')->toArray();
            $this->showDeleteSelected = true;
        } else {
            $this->selectedBookshelves = [];
            $this->showDeleteSelected = false;
        }
    }

    public function deleteSelected()
    {
        Bookshelf::whereIn('id', $this->selectedBookshelves)->delete();
        session()->flash('success', 'Selected Book Categories successfully deleted.');
        $this->selectedBookshelves = [];

        $this->showDeleteSelected = false;
        $this->dispatch('closeModal');
        
    }

    public function fetchBookCategories()
    {
        $query = Bookshelf::query();

        if ($this->search) {
            $query->where('bookshelf_number', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
        }

        if ($this->sortBy == 'bookshelf-asc') {
            $query->orderBy('bookshelf_number', 'asc');
        } elseif ($this->sortBy == 'bookshelf-desc') {
            $query->orderBy('bookshelf_number', 'desc');
        } elseif ($this->sortBy == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($this->sortBy == 'oldest') {
            $query->orderBy('created_at', 'asc');
        }

        return $query->paginate($this->perPage);
    }
    public function render()
    {
        $bookshelves = $this->fetchBookCategories();
        $optionPages = ['10','20','40','50','100'];
        $columns = ['#','Bookshelf Number','Added or Edited','Actions'];
        return view('livewire.bookshelves.index',compact('bookshelves','optionPages','columns'));
    }
}
