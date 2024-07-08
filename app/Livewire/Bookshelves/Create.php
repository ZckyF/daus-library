<?php

namespace App\Livewire\Bookshelves;

use App\Livewire\Forms\BookshelfForm;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Create Bookshelf')]
class Create extends Component
{

    public BookshelfForm $form;

    public function save()
    {
        $this->form->store();
        $this->redirectRoute('bookshelves');
    }

    public function render()
    {
        return view('livewire.bookshelves.create');
    }
}
