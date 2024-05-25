<?php

namespace Tests\Feature\Models;

use App\Models\Book;
use App\Models\BorrowingBook;
use App\Models\Employee;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BorrowingBookPivotTest extends TestCase
{
    use RefreshDatabase;

    public function testAttachBorrowingBookToBook(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);
        $borrowingBook = BorrowingBook::factory()->create([
            'user_id' => $user->id,
            'member_id' => $member->id
        ]);
        $book = Book::factory()->create(['user_id' => $user->id]);
        
        $borrowingBook->books()->attach($book->id);
        
        $this->assertDatabaseHas('borrowing_book_pivot', [
            'book_id' => $book->id,
            'borrowing_book_id' => $borrowingBook->id,
        ]);
    }

    public function testDetachBookFromBookCategory(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);
        $borrowingBook = BorrowingBook::factory()->create([
            'user_id' => $user->id,
            'member_id' => $member->id
        ]);
        $book = Book::factory()->create(['user_id' => $user->id]);


        $borrowingBook->books()->attach([$book->id]);

        $borrowingBook->books()->detach([$book->id]);


        $this->assertSoftDeleted('borrowing_book_pivot', [
            'book_id' => $book->id,
            'borrowing_book_id' => $borrowingBook->id,
        ]);
    }

    public function testBorrowingBookPivotSoftDeletes(): void
    {

        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);
        $borrowingBook = BorrowingBook::factory()->create([
            'user_id' => $user->id,
            'member_id' => $member->id
        ]);
        $book = Book::factory()->create(['user_id' => $user->id]);

        $borrowingBook->books()->attach($book->id);

        // Soft delete the book
        $book->delete();
        // Detach the book
        $borrowingBook->books()->detach($book->id);

        $this->assertSoftDeleted('books', [
            'id' => $book->id,
        ]);
        $this->assertSoftDeleted('borrowing_book_pivot', [
            'book_id' => $book->id,
            'borrowing_book_id' => $borrowingBook->id,
        ]);
    }
}
