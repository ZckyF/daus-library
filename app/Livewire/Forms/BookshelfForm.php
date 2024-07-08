<?php

namespace App\Livewire\Forms;

use App\Models\Bookshelf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
        
        
        Bookshelf::create(array_merge($this->validate($rules), [
            'user_id' => Auth::user()->id
        ]));

        $this->resetForm();

        session()->flash('success', 'Bookshelf created successfully');
    }

    public function update($bookshelfId)
    {
        $bookshelf = Bookshelf::findOrfail($bookshelfId);

        $rules = $this->rules();
        $rules['bookshelf_number'] .= '|'.Rule::unique('bookshelves', 'bookshelf_number')->ignore($bookshelf->id);


        $bookshelf->update(array_merge($this->validate($rules),[
            'user_id' => Auth::user()->id
        ]));
        $this->resetForm();
        
        session()->flash('success', 'Bookshelf successfully updated.');
    }

    protected function resetForm()
    {
        $this->reset(['bookshelf_number', 'location']);
    }
}
