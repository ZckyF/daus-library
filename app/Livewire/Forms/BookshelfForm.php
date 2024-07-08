<?php

namespace App\Livewire\Forms;

use App\Models\Bookshelf;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BookshelfForm extends Form
{
    public $bookshelf_number;
    public $location;

    public function rules()
    {
        return [
            'bookshelf_number' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ];
    }

    public function store()
    {
        $rules = $this->rules();
        $rules['bookshelf_number'] .= '|unique:bookshelves,bookshelf_number';
        
        $validatedData = $this->validate($rules);
        
        Bookshelf::create(array_merge($validatedData, [
            'user_id' => Auth::user()->id
        ]));

        $this->resetForm();

        session()->flash('success', 'Bookshelf created successfully');
    }

    protected function resetForm()
    {
        $this->reset(['bookshelf_number', 'location']);
    }
}
