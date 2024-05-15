<?php

namespace Database\Seeders;

use App\Models\BorrowingBookPivot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowingBookPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BorrowingBookPivot::create([
            'borrowing_book_id' => 1,
            'book_id' => 1
        ]);
    
        BorrowingBookPivot::create([
            'borrowing_book_id' => 1,
            'book_id' => 2
        ]);
    
        BorrowingBookPivot::create([
            'borrowing_book_id' => 2,
            'book_id' => 3
        ]);
    
        BorrowingBookPivot::create([
            'borrowing_book_id' => 3,
            'book_id' => 4
        ]);
    
        BorrowingBookPivot::create([
            'borrowing_book_id' => 4,
            'book_id' => 5
        ]);
    }
}
