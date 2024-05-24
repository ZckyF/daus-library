<?php

namespace Tests\Feature\Models;

use App\Models\Book;
use App\Models\Bookshelf;
use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookshelfPivotTest extends TestCase
{
    use  RefreshDatabase;

    /**
     * Test that a book can be attached to a bookshelf.
     *
     * This test creates an employee, a user, a book, and a bookshelf. 
     * It attaches the book to the bookshelf and then asserts that the book is present 
     * in the 'bookshelf_pivot' table with the correct book_id and bookshelf_id.
     *
     * @return void
     */
    public function testAttachBookToBookshelf(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookshelf = Bookshelf::factory()->create(['user_id' => $user->id]);
        $book = Book::factory()->create(['user_id' => $user->id]);

        $bookshelf->books()->attach($book->id);
        
        $this->assertDatabaseHas('bookshelf_pivot', [
            'book_id' => $book->id,
            'bookshelf_id' => $bookshelf->id,
        ]);
    }
    /**
     * Test that a book can be detached from a bookshelf.
     *
     * This test creates an employee, a user, a book, and a bookshelf. 
     * It attaches the book to the bookshelf and then detaches the book from the bookshelf. 
     * Finally, it asserts that the book is soft deleted
     * in the 'bookshelf_pivot' table with the correct book_id and bookshelf_id.
     *
     * @return void
     */
    public function testDetachBookFromBookshelf(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookshelf = Bookshelf::factory()->create(['user_id' => $user->id]);
        $book = Book::factory()->create(['user_id' => $user->id]);


        $bookshelf->books()->attach([$book->id]);

        $bookshelf->books()->detach([$book->id]);


        $this->assertSoftDeleted('bookshelf_pivot', [
            'book_id' => $book->id,
            'bookshelf_id' => $bookshelf->id,
        ]);
    }

    /**
     * Test that a book can be soft deleted from a bookshelf.
     *
     * This test creates an employee, a user, a book, and a bookshelf. 
     * It attaches the book to the bookshelf, soft deletes the book, 
     * and then detaches the book from the bookshelf. 
     * Finally, it asserts that the book is soft deleted in the 'books' table 
     * with the correct id and that it is also soft deleted in the 
     * 'book_category_pivot' table with the correct book_id and book_category_id.
     *
     * @return void
     */
    public function testBookshelfPivotSoftDeletes(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookshelf = Bookshelf::factory()->create(['user_id' => $user->id]);
        $book = Book::factory()->create(['user_id' => $user->id]);

        $bookshelf->books()->attach($book->id);

        // Soft delete the book
        $book->delete();
        // Detach the book
        $bookshelf->books()->detach($book->id);

        $this->assertSoftDeleted('books', [
            'id' => $book->id,
        ]);
        $this->assertSoftDeleted('bookshelf_pivot', [
            'book_id' => $book->id,
            'bookshelf_id' => $bookshelf->id,
        ]);
    }
}
