<?php

namespace App\Livewire\Bookshelves;

use App\Livewire\Forms\BookshelfForm;
use App\Models\Bookshelf;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Bookshelf')]
class Edit extends Component
{
    /**
     * Username of the user.
     * 
     * @var string
     */
    public string $user;

    /**
     * Instance of BookshelfForm.
     * 
     * @var BookshelfForm
     */
    public BookshelfForm $form;

    /**
     * Initialize the component with a bookshelf number.
     * 
     * @param string $bookshelf_number
     * @return void
     */
    public function mount(string $bookshelf_number): void
    {
        $bookshelf = Bookshelf::where('bookshelf_number', $bookshelf_number)->firstOrFail();

        if (!$bookshelf) {
            abort(404);
        }
        
        $this->user = $bookshelf->user->username;
        $this->form->setBookshelf($bookshelf);
    }

    /**
     * Save the updated bookshelf.
     * 
     * @return void
     */
    public function save(): void
    {
        $this->form->update();
        $this->redirectRoute('bookshelves');
    }

    /**
     * Delete the current bookshelf.
     * 
     * @return void
     */
    public function delete(): void
    {
        $bookshelf = $this->form->bookshelf;
        $bookshelf->delete();
        session()->flash('success', 'Bookshelf deleted successfully');
        $this->redirectRoute('bookshelves');
    }

    /**
     * Render the component.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $isEditPage = true;
        return view('livewire.bookshelves.edit', compact('isEditPage'));
    }
}
