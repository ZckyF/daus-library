<?php

namespace App\Livewire\Forms;

use App\Models\BookCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;
use Livewire\Form;

class BookCategoryForm extends Form
{
    public ?BookCategory $bookCategory;
    public $category_name;
    public $description;

    public function rules()
    {
        return [
            'category_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ];
    }

    public function setBookCategory(BookCategory $bookCategory)
    {
        $this->bookCategory = $bookCategory;
        $this->category_name = $bookCategory->category_name;
        $this->description = $bookCategory->description;
    }
    
    public function store()
    {
        $rules = $this->rules();
        $rules['category_name'] .= '|unique:book_categories,category_name';

       BookCategory::create(array_merge($this->validate($rules), [
           'user_id' => Auth::user()->id
       ]));

      $this->reset();

       session()->flash('success', 'Book Category created successfully');
    }

    public function update()
    {
        $bookCategory = $this->bookCategory;

        $rules = $this->rules();
        $rules['category_name'] .= '|'.Rule::unique('book_categories', 'category_name')->ignore($bookCategory);


        $bookCategory->update(array_merge($this->validate($rules),[
            'user_id' => Auth::user()->id
        ]));
       $this->reset();

        session()->flash('success', 'Book Category successfully updated.');
    }
}
