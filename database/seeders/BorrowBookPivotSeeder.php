<?php

namespace Database\Seeders;


use App\Models\BorrowBookPivot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowBookPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BorrowBookPivot::create([
            'borrow_book_id' => 1,
            'book_id' => 1
        ]);
    
        BorrowBookPivot::create([
            'borrow_book_id' => 1,
            'book_id' => 2
        ]);
    
        BorrowBookPivot::create([
            'borrow_book_id' => 2,
            'book_id' => 3
        ]);
    
        BorrowBookPivot::create([
            'borrow_book_id' => 3,
            'book_id' => 4
        ]);
    
        BorrowBookPivot::create([
            'borrow_book_id' => 4,
            'book_id' => 5
        ]);
    }
}
