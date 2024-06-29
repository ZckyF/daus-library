<?php

namespace App\Livewire\BookCategories;

use App\Livewire\Forms\BookCategoryForm;
use App\Livewire\Forms\BookForm;
use Livewire\Component;

class Create extends Component
{
    public BookCategoryForm $form;

    public function save()
    {
        $this->form->store();
        $this->redirectRoute('book-categories');
    }

    
    public function render()
    {
        return view('livewire.book-categories.create');
    }
}
