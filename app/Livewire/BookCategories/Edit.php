<?php

namespace App\Livewire\BookCategories;

use App\Livewire\Forms\BookCategoryForm;
use App\Models\BookCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Edit Book Category')]
class Edit extends Component
{
    /**
     * Username of the user who created or edited the book category.
     * 
     * @var string
     */
    public string $user;

    /**
     * Form instance for editing book categories.
     * 
     * @var BookCategoryForm
     */
    public BookCategoryForm $form;

    /**
     * Initialize component with the category name slug to load the corresponding category.
     * 
     * @param string $category_name
     * @return void
     */
    public function mount(string $category_name): void
    {
        $categoryNameSlug = str_replace('-', ' ', $category_name);
        $category = BookCategory::where('category_name', $categoryNameSlug)->firstOrFail();

        if (!$category) {
            abort(404);
        }
        if (Gate::denies('view', $category)) {
            abort(403,'This action is unauthorized.');
        }
        
        $this->user = $category->user->username;

        $this->form->setBookCategory($category);
    }

    /**
     * Update the edited book category.
     * 
     * @return void
     */
    public function save(): void
    {
        if (Gate::denies('create', BookCategory::class)) {
            abort(403,'This action is unauthorized.');
        }
        $this->form->update();
        session()->flash('success', 'Book Category updated successfully.');
        $this->redirectRoute('book-categories');
    }

    /**
     * Delete the book category.
     * 
     * @return void
     */
    public function delete(): void
    {
        if(Gate::denies('update', $this->form->bookCategory)) {
            abort(403,'This action is unauthorized.');
        }
        $bookCategory = BookCategory::find($this->form->bookCategory->id);
        $bookCategory->delete();
        $bookCategory->update(['user_id' => Auth::user()->id]);
            
        session()->flash('success', 'Bookshelf successfully deleted');
    
        $this->redirectRoute('book-categories');
    }

    /**
     * Render the component view for editing book categories.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $isEditPage = true;
        return view('livewire.book-categories.edit', compact('isEditPage'));
    }
}

