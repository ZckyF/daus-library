<?php

namespace App\Livewire\Forms;

use App\Models\Bookshelf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BookshelfForm extends Form
{
    /**
     * The current bookshelf for the form.
     * 
     * @var Bookshelf|null
     */
    public ?Bookshelf $bookshelf = null;

    /**
     * The bookshelf number for the form.
     * 
     * @var string
     */
    public $bookshelf_number;
    /**
     * The location for the form.
     * 
     * @var string
     */
    public $location;
    
    /**
     * Validation rules for the form.
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            'bookshelf_number' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ];
    }

    /**
     * Set the current bookshelf for the form.
     * 
     * @param Bookshelf $bookshelf
     * @return void
     */
    public function setBookshelf(Bookshelf $bookshelf): void
    {
        $this->bookshelf = $bookshelf;
        $this->bookshelf_number = $bookshelf->bookshelf_number;
        $this->location = $bookshelf->location;
    }

    /**
     * Store a new bookshelf.
     * 
     * @return void
     */
    public function store(): void
    {
        $rules = $this->rules();
        $rules['bookshelf_number'] .= '|unique:bookshelves,bookshelf_number';
        
        Bookshelf::create(array_merge($this->validate($rules), [
            'user_id' => Auth::user()->id
        ]));

        $this->reset();

        session()->flash('success', 'Bookshelf created successfully');
    }

    /**
     * Update the current bookshelf.
     * 
     * @return void
     */
    public function update(): void
    {
        $bookshelf = $this->bookshelf;

        $rules = $this->rules();
        $rules['bookshelf_number'] .= '|'.Rule::unique('bookshelves', 'bookshelf_number')->ignore($bookshelf->id);

        $bookshelf->update(array_merge($this->validate($rules), [
            'user_id' => Auth::user()->id
        ]));

        $this->reset();
        session()->flash('success', 'Bookshelf successfully updated.');
    }
}
