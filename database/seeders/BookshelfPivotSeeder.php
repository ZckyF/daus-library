<?php

namespace Database\Seeders;

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
        for ($i = 1; $i <= 10; $i++) {
            BookshelfPivot::create([
                'book_id' => $i,
                'bookshelf_id' => $i,
            ]);
        }
    }
}
