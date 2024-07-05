<?php

namespace App\Livewire\BookCategories;

use App\Models\BookCategory;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Book Categories')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'newest';
    public $perPage = 10;
    public $bookCategoryId;
    public $selectedCategories = [];
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

    public function updatedSelectedCategories()
    {
        
        if(count($this->selectedCategories) > 0) {
            $this->showDeleteSelected = true;
        } else {
            $this->showDeleteSelected = false;
        }
       
    }
    public function setBookCategoryId($bookCategoryId)
    {
        $this->bookCategoryId = $bookCategoryId;
    }
    public function delete()
    {
       BookCategory::destroy($this->bookCategoryId);
       session()->flash('success', 'Book Category successfully deleted.');

       $this->dispatch('closeModal');
    }
    public function toggleSelectAll()
    {
        if ($this->selectAllCheckbox) {
            $this->selectedCategories = BookCategory::pluck('id')->toArray();
            $this->showDeleteSelected = true;
        } else {
            $this->selectedCategories = [];
            $this->showDeleteSelected = false;
        }
    }

    public function deleteSelected()
    {
        BookCategory::whereIn('id', $this->selectedCategories)->delete();
        session()->flash('success', 'Selected Book Categories successfully deleted.');
        $this->selectedCategories = [];

        $this->showDeleteSelected = false;
        $this->dispatch('closeModal');
        
    }

    public function fetchBookCategories()
    {
        $query = BookCategory::query();

        if ($this->search) {
            $query->where('category_name', 'like', '%' . $this->search . '%');
        }

        if ($this->sortBy == 'category-asc') {
            $query->orderBy('category_name', 'asc');
        } elseif ($this->sortBy == 'category-desc') {
            $query->orderBy('category_name', 'desc');
        } elseif ($this->sortBy == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($this->sortBy == 'oldest') {
            $query->orderBy('created_at', 'asc');
        }

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        $categories = $this->fetchBookCategories();
        $optionPages = ['10','20','40','50','100'];
        $columns = ['#','Category Name','Added or Edited','Actions'];
        
        return view('livewire.book-categories.index', 
            compact('categories','optionPages','columns')
        );
    }
}
