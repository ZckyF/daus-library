<?php

namespace App\Livewire\Forms;

use App\Models\BorrowBook;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BorrowBookForm extends Form
{
    public ?BorrowBook $borrowBook = null;

    public $borrow_number;
    public $member_name;
    public $borrow_date;
    public $return_date;
    public $returned_date;
    public $quantity;
    public $status;

    public function rules(): array
    {
        return [
            'status' => 'required|string|max:255|in:borrowed,lost,due,damaged,returned',
        ];
    }
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
