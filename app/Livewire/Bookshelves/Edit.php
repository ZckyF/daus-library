<?php

namespace App\Livewire\Bookshelves;

use App\Livewire\Forms\BookshelfForm;
use App\Models\Bookshelf;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Bookshelf')]
class Edit extends Component
{
    public $bookshelfId;
    public $user;

    public BookshelfForm $form;

    public function mount($bookshelf_number)
    {
        $bookshelf = Bookshelf::where('bookshelf_number', $bookshelf_number)->firstOrFail();

        if (!$bookshelf) {
            abort(404);
        }
        
        $this->bookshelfId = $bookshelf->id;
        $this->user = $bookshelf->user->username;


        $this->form->bookshelf_number = $bookshelf->bookshelf_number;
        $this->form->location = $bookshelf->location;


    }

    public function save()
    {
        $this->form->update($this->bookshelfId);
        $this->redirectRoute('bookshelves');
    }

    public function delete()
    {
        $bookshelf = Bookshelf::find($this->bookshelfId);
        $bookshelf->delete();
        session()->flash('success','Bookshelf deleted successfully');
        $this->redirectRoute('bookshelves');
    }
    public function render()
    {
        $isEditPage = true;
        return view('livewire.bookshelves.edit', compact('isEditPage'));
    }
}
