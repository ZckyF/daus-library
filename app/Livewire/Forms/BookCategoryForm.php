<?php

namespace App\Livewire\Forms;

use App\Models\BookCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;
use Livewire\Form;

class BookCategoryForm extends Form
{
    /**
     * Optional existing book category model instance.
     * 
     * @var BookCategory|null
     */
    public ?BookCategory $bookCategory = null;

    /**
     * Name of the book category.
     * 
     * @var string
     */
    public $category_name;

    /**
     * Description of the book category.
     * 
     * @var string
     */
    public $description;

    /**
     * Validation rules for book category creation/updation.
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            'category_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ];
    }

    /**
     * Set the book category instance for editing.
     * 
     * @param BookCategory $bookCategory
     * @return void
     */
    public function setBookCategory(BookCategory $bookCategory): void
    {
        $this->bookCategory = $bookCategory;
        $this->category_name = $bookCategory->category_name;
        $this->description = $bookCategory->description;
    }
    
    /**
     * Store a new book category record.
     * 
     * @return void
     */
    public function store(): void
    {
        $rules = $this->rules();
        $rules['category_name'] .= '|unique:book_categories,category_name';

        BookCategory::create(array_merge($this->validate($rules), [
            'user_id' => Auth::user()->id
        ]));

        $this->reset();

        session()->flash('success', 'Book Category created successfully');
    }

    /**
     * Update an existing book category record.
     * 
     * @return void
     */
    public function update(): void
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

