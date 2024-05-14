<?php

namespace Database\Seeders;

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
        for ($i = 1; $i <= 10; $i++) {
            BookCategoryPivot::create([
                'book_id' => $i,
                'book_category_id' => $i,
            ]);
        }
    }
}
