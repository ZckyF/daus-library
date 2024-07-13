<?php

namespace App\Livewire\Bookshelves;

use App\Livewire\Forms\BookshelfForm;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Create Bookshelf')]
class Create extends Component
{
    /**
     * Instance of BookshelfForm.
     * 
     * @var BookshelfForm
     */
    public BookshelfForm $form;

    /**
     * Save the new bookshelf.
     * 
     * @return void
     */
    public function save(): void
    {
        $this->form->store();
        $this->redirectRoute('bookshelves');
    }

    /**
     * Render the component.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.bookshelves.create');
    }
}