<?php

namespace Database\Seeders;

use App\Models\BorrowingBook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowingBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BorrowingBook::factory(10)->create();
    }
}
