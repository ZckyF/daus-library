<?php

namespace App\Livewire\BookCategories;

use App\Livewire\Forms\BookCategoryForm;
use App\Livewire\Forms\BookForm;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Create Book Category')]
class Create extends Component
{
    /**
     * Form instance for creating new book categories.
     * 
     * @var BookCategoryForm
     */
    public BookCategoryForm $form;

    /**
     * Store the newly created book category.
     * 
     * @return void
     */
    public function save(): void
    {
        $this->form->store();
        session()->flash('success', 'Book Category created successfully.');
        $this->redirectRoute('book-categories');
    }

    /**
     * Render the component view for creating book categories.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.book-categories.create');
    }
}

