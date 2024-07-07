<?php

namespace App\Livewire\BookCategories;

use App\Livewire\Forms\BookCategoryForm;
use App\Models\BookCategory;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Edit Book Category')]
class Edit extends Component
{
    public $bookCategoryId;
    public $user;

    public BookCategoryForm $form;

    public function mount($category_name)
    {
        $categoryNameSlug = str_replace('-', ' ', $category_name);
        $category = BookCategory::where('category_name', $categoryNameSlug)->firstOrFail();

        if (!$category) {
            abort(404);
        }
        
        $this->bookCategoryId = $category->id;
        $this->user = $category->user->username;


        $this->form->category_name = $category->category_name;
        $this->form->description = $category->description;


    }

    public function save()
    {
        $this->form->update($this->bookCategoryId);
        $this->redirectRoute('book-categories');
    }

    public function delete()
    {
        $book = BookCategory::find($this->bookCategoryId);
        $book->delete();
        session()->flash('success','Book Category deleted successfully');
        $this->redirectRoute('book-categories');
    }

    public function render()
    {
        $isEditPage = true;
        return view('livewire.book-categories.edit',compact('isEditPage'));
    }
}
