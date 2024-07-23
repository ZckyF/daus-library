<?php

namespace App\Livewire\Bookshelves;

use App\Livewire\Forms\BookshelfForm;
use App\Models\Bookshelf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        $this->form->setBookshelf($bookshelf);
        if (!$bookshelf) {
            abort(404);
        }
        if(Gate::denies('view', $this->form->bookshelf)) {
            abort(403,'This action is unauthorized.');
        }
        
        $this->user = $bookshelf->user->username;
    }

    /**
     * Save the updated bookshelf.
     * 
     * @return void
     */
    public function save(): void
    {
        if(Gate::denies('update', $this->form->bookshelf)) {
            abort(403,'This action is unauthorized.');
        }
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
        if(Gate::denies('update', $this->form->bookshelf)) {
            abort(403,'This action is unauthorized.');
        }
        $bookshelf = Bookshelf::find($this->form->bookshelf->id);
        $bookshelf->delete();
        $bookshelf->update(['user_id' => Auth::user()->id]);
            
        session()->flash('success', 'Bookshelf successfully deleted');
    
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
