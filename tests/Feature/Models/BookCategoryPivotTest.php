<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Employee;
use App\Models\BookCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookCategoryPivotTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a book can be attached to a category.
     *
     * This test creates an employee, a user, a book category, and a book. 
     * It then attaches the book to the book category and asserts 
     * that the book is associated with the category in the database.
     *
     * @return void
     */
    public function testAttachBookToCategory(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookCategory = BookCategory::factory()->create(['user_id' => $user->id]);
        $book = Book::factory()->create(['user_id' => $user->id]);

        $bookCategory->books()->attach($book->id);
        
        $this->assertDatabaseHas('book_category_pivot', [
            'book_id' => $book->id,
            'book_category_id' => $bookCategory->id,
        ]);
    }

    /**
     * Test that a book can be detached from a category.
     *
     * This test creates an employee, a user, a book category, and a book. 
     * It then attaches the book to the book category and detaches it. 
     * Finally, it asserts that the book is soft deleted from 
     * the book_category_pivot table in the database.
     *
     * @return void
     */
    public function testDetachBookFromCategory(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookCategory = BookCategory::factory()->create(['user_id' => $user->id]);
        $book = Book::factory()->create(['user_id' => $user->id]);


        $bookCategory->books()->attach([$book->id]);

        $bookCategory->books()->detach([$book->id]);


        $this->assertSoftDeleted('book_category_pivot', [
            'book_id' => $book->id,
            'book_category_id' => $bookCategory->id,
        ]);
    }

    /**
     * Test that the book category pivot table is soft deleted when a book is soft deleted.
     *
     * This test creates an employee, a user, a book category, and a book. 
     * It then attaches the book to the book category and soft deletes the book. 
     * Finally, it asserts that the book is soft deleted from the 'books' table and 
     * the book category pivot table in the 'book_category_pivot' table.
     *
     * @return void
     */
    public function testBookCategoryPivotSoftDeletes(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookCategory = BookCategory::factory()->create(['user_id' => $user->id]);
        $book = Book::factory()->create(['user_id' => $user->id]);

        $bookCategory->books()->attach($book->id);

        // Soft delete the book
        $book->delete();
        // Detach the book
        $bookCategory->books()->detach($book->id);

        $this->assertSoftDeleted('books', [
            'id' => $book->id,
        ]);
        $this->assertSoftDeleted('book_category_pivot', [
            'book_id' => $book->id,
            'book_category_id' => $bookCategory->id,
        ]);
    }
}
