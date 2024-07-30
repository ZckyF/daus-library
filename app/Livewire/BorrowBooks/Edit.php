<?php

namespace App\Livewire\BorrowBooks;

use App\Livewire\Forms\BorrowBookForm;
use App\Models\BorrowBook;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Borrow Book')]
class Edit extends Component
{
    /**
     * Username of the user.
     * 
     * @var string
     */
    public string $user;

    /**
     * Instance of BorrowBookForm.
     * 
     * @var BorrowBookForm
     */
    public BorrowBookForm $form;

    /**
     * Initialize the component with a borrow number.
     * 
     * @param string $borrow_number
     * @return void
     */
    public function mount(string $borrow_number): void
    {
        $borrowBook = BorrowBook::where('borrow_number', $borrow_number)->firstOrFail();
        $this->form->setBorrowBook($borrowBook);
        if (!$borrowBook) {
            abort(404);
        }
        if(Gate::denies('view', $this->form->borrowBook)) {
            abort(403);
        }
        
        $this->user = $borrowBook->user->username;
    }
    /**
     * Update the returned date.
     * 
     * @return void
     */
    public function updateReturnedDate(): void
    {
        if($this->form->status !== 'Borrowed' && $this->form->status !== 'Due') $this->form->returned_date = Carbon::now()->format('Y-m-d');
         else $this->form->returned_date = null;
    }
    /**
     * Save the updated borrow book.
     * 
     * @return void
     */
    public function save(): void
    {
        if(Gate::denies('update', $this->form->borrowBook)) {
            abort(403);
        }
        $this->form->update();
        $this->redirectRoute('borrow-books');
    }

    /**
     * Delete the current borrow book.
     * 
     * @return void
     */
    public function delete(): void
    {
        if(Gate::denies('update', $this->form->borrowBook)) {
            abort(403,'This action is unauthorized.');
        }
        $borrowBook = BorrowBook::find($this->form->borrowBook->id);
        $borrowBook->delete();
        $borrowBook->update(['user_id' => Auth::user()->id]);
            
        session()->flash('success', 'Borrow book successfully deleted');
    
        $this->redirectRoute('borrow-books');
    }

    /**
     * Render the component.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $statuses = [
            'borrowed' => 'Borrowed', 
            'returned' => 'Returned',
            'losy' => 'Lost',
            'damaged' => 'Damaged',
            'due' => 'Due',
        ];
        return view('livewire.borrow-books.edit', compact('statuses'));
    }
}
