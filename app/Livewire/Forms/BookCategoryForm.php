<?php

namespace App\Livewire\Forms;

use App\Models\BookCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Rule;
use Livewire\Form;

class BookCategoryForm extends Form
{
    #[Rule('required|string|max:255|unique:book_categories,category_name')]
    public $category_name;
    #[Rule('required|string')]
    public $description;
    
    public function store()
    {
       $validatedData = $this->validate();

       BookCategory::create(array_merge($validatedData, [
           'user_id' => Auth::user()->id
       ]));

       $this->resetForm();

       session()->flash('success', 'Book Category created successfully');
    }

    public function resetForm()
    {
        $this->reset(['category_name', 'description']);
    }
}
