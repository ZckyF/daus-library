<?php

namespace Tests\Feature\Models;

use App\Models\Book;
use App\Models\BorrowBook;
use App\Models\Employee;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BorrowBookPivotTest extends TestCase
{
    use RefreshDatabase;

    public function testAttachBorrowBookToBook(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);
        $borrowBook = BorrowBook::factory()->create([
            'user_id' => $user->id,
            'member_id' => $member->id
        ]);
        $book = Book::factory()->create(['user_id' => $user->id]);
        
        $borrowBook->books()->attach($book->id);
        
        $this->assertDatabaseHas('borrow_book_pivot', [
            'book_id' => $book->id,
            'borrow_book_id' => $borrowBook->id,
        ]);
    }

    public function testDetachBookFromBookCategory(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);
        $borrowBook = BorrowBook::factory()->create([
            'user_id' => $user->id,
            'member_id' => $member->id
        ]);
        $book = Book::factory()->create(['user_id' => $user->id]);


        $borrowBook->books()->attach([$book->id]);

        $borrowBook->books()->detach([$book->id]);


        $this->assertSoftDeleted('borrow_book_pivot', [
            'book_id' => $book->id,
            'borrow_book_id' => $borrowBook->id,
        ]);
    }

    public function testBorrowBookPivotSoftDeletes(): void
    {

        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);
        $borrowBook = BorrowBook::factory()->create([
            'user_id' => $user->id,
            'member_id' => $member->id
        ]);
        $book = Book::factory()->create(['user_id' => $user->id]);

        $borrowBook->books()->attach($book->id);

        // Soft delete the book
        $book->delete();
        // Detach the book
        $borrowBook->books()->detach($book->id);

        $this->assertSoftDeleted('books', [
            'id' => $book->id,
        ]);
        $this->assertSoftDeleted('borrow_book_pivot', [
            'book_id' => $book->id,
            'borrow_book_id' => $borrowBook->id,
        ]);
    }
}
