<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class BookForm extends Form
{
    public $books, $bookId, $title, $author, $published_year, $price_per_book, $quantity, $description, $cover_image;
    
    
    public $selectedCategories = '';
    public $selectedBookshelves = '';
    
    

    
}
