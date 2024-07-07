<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookCategoryPivot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookCategoryPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = Book::all();
        $bookCategories = BookCategory::all();

        foreach ($books as $book) {
        
            $randomCategoryCount = rand(1, 5); 

           
            $randomCategories = $bookCategories->random($randomCategoryCount);

            foreach ($randomCategories as $category) {
                BookCategoryPivot::create([
                    'book_id' => $book->id,
                    'book_category_id' => $category->id,
                ]);
            }
        }
    }
}
