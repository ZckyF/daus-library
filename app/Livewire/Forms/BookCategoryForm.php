<?php

namespace App\Livewire\Forms;

use App\Models\BookCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;
use Livewire\Form;

class BookCategoryForm extends Form
{

    public $category_name;
    public $description;

    protected function rules()
    {
        return [
            'category_name' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }
    
    public function store()
    {
        $rules = $this->rules();
        $rules['category_name'] .= '|unique:book_categories,category_name';

       BookCategory::create(array_merge($this->validate($rules), [
           'user_id' => Auth::user()->id
       ]));

       $this->resetForm();

       session()->flash('success', 'Book Category created successfully');
    }

    public function update($bookCategoryId)
    {
        $category = BookCategory::findOrfail($bookCategoryId);

        $rules = $this->rules();
        $rules['category_name'] .= '|'.Rule::unique('book_categories', 'category_name')->ignore($category->id);


        $category->update(array_merge($this->validate($rules),[
            'user_id' => Auth::user()->id
        ]));
        $this->resetForm();

        session()->flash('success', 'Book Category successfully updated.');
    }

    protected function resetForm()
    {
        $this->reset(['category_name', 'description']);
    }
}
