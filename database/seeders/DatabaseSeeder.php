<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmployeeSeeder::class,
            UserSeeder::class,
            MemberSeeder::class,
            BookSeeder::class,
            BookCategorySeeder::class,
            BookCategoryPivotSeeder::class,
            BookshelfSeeder::class,
            BookshelfPivotSeeder::class,
            BorrowBookSeeder::class,
            BorrowBookPivotSeeder::class,
            FineSeeder::class
        ]);
    }
}
