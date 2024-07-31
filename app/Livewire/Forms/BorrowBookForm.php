<?php

namespace App\Livewire\Forms;

use App\Models\BorrowBook;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BorrowBookForm extends Form
{
    /**
     * Optional existing borrow book model instance.
     * 
     * @var BorrowBook
     */
    public ?BorrowBook $borrowBook = null;
    /**
     * The number of the borrow book.
     * 
     * @var string
     */
    public $borrow_number;
    /**
     * The name of the member.
     * 
     * @var string
     */
    public $member_name;
    /**
     * The date of the borrow book.
     * 
     * @var string
     */
    public $borrow_date;
    /**
     * The date of the return book.
     * 
     * @var string
     */
    public $return_date;
    /**
     * The date of the returned book.
     * 
     * @var string
     */
    public $returned_date;
    /**
     * The quantity of the borrow book.
     * 
     * @var int
     */
    public $quantity;
    /**
     * The status of the borrow book.
     * 
     * @var string
     */
    public $status;
    /**
     * Validation rules for the form.
     * 
     * @var bool
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string|max:255|in:borrowed,lost,due,damaged,returned',
        ];
    }
    /**
     * Set the borrow book model instance.
     * 
     * @param BorrowBook $borrowBook
     */
    public function setBorrowBook(BorrowBook $borrowBook): void
    {
        $this->borrowBook = $borrowBook;
        $this->borrow_number = $borrowBook->borrow_number;
        $this->member_name = $borrowBook->member->full_name;
        $this->borrow_date = Carbon::parse($borrowBook->borrow_date)->format('Y-m-d');
        $this->return_date = Carbon::parse($borrowBook->return_date)->format('Y-m-d');
        $this->returned_date = $borrowBook->returned_date ? Carbon::parse($borrowBook->returned_date)->format('Y-m-d') : null;
        $this->quantity = $borrowBook->quantity;
        $this->status = $borrowBook->status;
    }
    /**
     * Update the borrow book.
     * 
     * @return void
     */
    public function update(): void
    {
        $this->validate();
        $this->borrowBook->update([
            'status' => $this->status,
            'returned_date' => $this->returned_date
        ]);
        session()->flash('success', 'Borrow book updated successfully');

        $this->reset();
    }
}
