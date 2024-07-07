<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Bookshelf;
use App\Models\BookshelfPivot;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookshelfPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = Book::all();
        $bookshelves = Bookshelf::all();

        foreach ($books as $book) {

            $randomBookshelfCount = rand(1, 3); 

            $randomBookshelves = $bookshelves->random($randomBookshelfCount);

            foreach ($randomBookshelves as $bookshelf) {
                BookshelfPivot::create([
                    'book_id' => $book->id,
                    'bookshelf_id' => $bookshelf->id,
                ]);
            }
        }
    }
}
