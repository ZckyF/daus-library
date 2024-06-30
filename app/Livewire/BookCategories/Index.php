<?php

namespace App\Livewire\BookCategories;

use App\Models\BookCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'newest';
    public $perPage = 10;


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

    public function fetchCBookCategories()
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
        $categories = $this->fetchCBookCategories();
        $optionPages = ['10','20','40','50','100'];

        return view('livewire.book-categories.index', 
            compact('categories','optionPages')
        );
    }
}
