<?php

namespace Tests\Feature\Models;

use App\Models\Book;
use App\Models\BorrowingBook;
use App\Models\Employee;
use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BorrowingBookTest extends TestCase
{
    use RefreshDatabase;
    /**
     * An array of required fields.
     * 
     * @var array<string>
     */
    private array $requiredFields = [
        'borrowing_number',
        'member_id',
        'user_id',
        'status',
    ];
    
    
    /**
     * Test the creation of a borrowing book.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrowing book with the provided member and user IDs.
     * Finally, it asserts that the created borrowing book is an instance of the BorrowingBook class
     * and that it exists in the 'borrowing_books' table in the database.
     *
     * @return void
     */
    public function testBorrowingBookCreation(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);
        

        $borrowingBook = BorrowingBook::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(BorrowingBook::class, $borrowingBook);
        $this->assertDatabaseHas('borrowing_books', [
            'id' => $borrowingBook->id,
        ]);
    }

    /**
     * Test that all required fields for the BorrowingBook model are required.
     *
     * This function creates a new BorrowingBook instance and sets all required fields to null.
     * It then attempts to save the instance, which should result in a QueryException
     * due to the required fields being null.
     *
     * @throws QueryException If any required field is null.
     * @return void
     */
    public function testRequiredFields(): void
    {
        $borrowingBook = new BorrowingBook();

        foreach ($this->requiredFields as $field) {
            $borrowingBook->{$field} = null;
        }

        $this->expectException(QueryException::class);
        $borrowingBook->save();
    }

    /**
     * Test that the timestamp fields in the BorrowingBook model can be nullable.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrowing book with the provided member and user IDs,
     * and sets the borrow_date, return_date, and returned_date fields to null.
     * Finally, it asserts that all three timestamp fields are null.
     *
     * @return void
     */
    public function testTimeStampIsNullable(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        $borrowingBook = BorrowingBook::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
            'borrow_date' => null,
            'return_date' => null,
            'returned_date' => null,
        ]);

        $this->assertNull($borrowingBook->borrow_date);
        $this->assertNull($borrowingBook->return_date);
        $this->assertNull($borrowingBook->returned_date);
    }

    /**
     * Test that the borrowing number must be unique.
     *
     * This function expects a QueryException to be thrown when attempting to create
     * two records with the same borrowing number. It creates a new employee, user,
     * and member using factories, and then creates a borrowing book with a unique
     * borrowing number. It then attempts to create another record with the same
     * borrowing number, which should result in a QueryException.
     *
     * @throws QueryException If attempting to create two records with the same borrowing number.
     * @return void
     */
    public function testBorrowingNumberMustBeUnique(): void
    {
        $this->expectException(QueryException::class);

        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        BorrowingBook::factory()->create([
            'borrowing_number' => 'unique_number',
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        // Attempt to create another record with the same borrowing_number
        BorrowingBook::factory()->create([
            'borrowing_number' => 'unique_number',
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test that the default status of a borrowing book is 'borrowed'.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrowing book with a random borrowing number, borrow date,
     * return date, and quantity. The member and user IDs are set to the newly created
     * member and user, respectively. The returned borrowing book is then found using
     * its ID and its status is asserted to be 'borrowed'.
     *
     * @return void
     */
    public function testDefaultStatusIsBorrowed(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        $borrowingBook = BorrowingBook::create([
            'borrowing_number' => 'BN' . mt_rand(100000000, 999999999),
            'borrow_date' => Carbon::now()->subDays(mt_rand(1, 30)),
            'return_date' => Carbon::now()->addDays(mt_rand(1, 30)),
            'returned_date' => null,
            'quantity' => mt_rand(1,3),
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);
        $borrowingBookFind = BorrowingBook::find($borrowingBook->id);

        $this->assertEquals('borrowed', $borrowingBookFind->status);
    }

    /**
     * Test that a borrowing book belongs to a member.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrowing book with the provided member and user IDs.
     * Finally, it asserts that the created borrowing book belongs to the member
     * and that the member IDs match.
     *
     * @return void
     */
    public function testBorrowingBookBelongsToMember(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        $borrowingBook = BorrowingBook::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Member::class, $borrowingBook->member);
        $this->assertEquals($member->id, $borrowingBook->member->id);
    }

    /**
     * Test that a borrowing book belongs to a user.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrowing book with the provided member and user IDs.
     * Finally, it asserts that the created borrowing book belongs to the user
     * and that the user IDs match.
     *
     * @return void
     */
    public function testBorrowingBookBelongsToUser(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        $borrowingBook = BorrowingBook::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $borrowingBook->user);
        $this->assertEquals($user->id, $borrowingBook->user->id);
    }

    /**
     * Test that a borrowing book belongs to many books.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrowing book with the provided member and user IDs.
     * Next, it creates two books with the same user ID.
     * The books are then attached to the borrowing book.
     * The relationships are reloaded.
     * The function then tests the user and borrowing book relationship,
     * the borrowing book and books relationship,
     * and the reverse relationship.
     *
     * @return void
     */
    public function testBorrowingBookBelongsToManyBooks(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);
        $borrowingBook = BorrowingBook::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);
        $book1 = Book::factory()->create(['user_id' => $user->id]);
        $book2 = Book::factory()->create(['user_id' => $user->id]);

        // Attach books to the borrowing book
        $borrowingBook->books()->attach([$book1->id, $book2->id]);

        // Reload relationships
        $borrowingBook = $borrowingBook->fresh();

        // Test user and borrowing book relationship
        $this->assertInstanceOf(User::class, $borrowingBook->user);
        $this->assertEquals($user->id, $borrowingBook->user->id);

        // Test borrowing book and books relationship
        $this->assertTrue($borrowingBook->books->contains($book1));
        $this->assertTrue($borrowingBook->books->contains($book2));

        // Test reverse relationship
        $this->assertTrue($book1->borrowingBooks->contains($borrowingBook));
        $this->assertTrue($book2->borrowingBooks->contains($borrowingBook));
    }

    /**
     * Test the soft delete functionality of the BorrowingBook model.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrowing book with the provided member and user IDs.
     * The borrowing book is soft deleted and the function asserts that the
     * borrowing book is soft deleted in the 'borrowing_books' table.
     *
     * @return void
     */
    public function testBorrowingBookSoftDelete(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        $borrowingBook = BorrowingBook::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        $borrowingBook->delete();

        $this->assertSoftDeleted('borrowing_books', [
            'id' => $borrowingBook->id,
        ]);
    }
}
