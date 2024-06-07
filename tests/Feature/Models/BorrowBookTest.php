<?php

namespace Tests\Feature\Models;

use App\Models\Book;
use App\Models\BorrowBook;
use App\Models\Employee;
use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BorrowBookTest extends TestCase
{
    use RefreshDatabase;
    /**
     * An array of required fields.
     * 
     * @var array<string>
     */
    private array $requiredFields = [
        'borrow_number',
        'member_id',
        'user_id',
        'status',
    ];
    
    
    /**
     * Test the creation of a borrow book.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrow book with the provided member and user IDs.
     * Finally, it asserts that the created borrow book is an instance of the BorrowBook class
     * and that it exists in the 'borrow_books' table in the database.
     *
     * @return void
     */
    public function testBorrowBookCreation(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);
        

        $borrowBook = BorrowBook::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(BorrowBook::class, $borrowBook);
        $this->assertDatabaseHas('borrow_books', [
            'id' => $borrowBook->id,
        ]);
    }

    /**
     * Test that all required fields for the BorrowngBook model are required.
     *
     * This function creates a new BorrowBook instance and sets all required fields to null.
     * It then attempts to save the instance, which should result in a QueryException
     * due to the required fields being null.
     *
     * @throws QueryException If any required field is null.
     * @return void
     */
    public function testRequiredFields(): void
    {
        $borrowBook = new BorrowBook();

        foreach ($this->requiredFields as $field) {
            $borrowBook->{$field} = null;
        }

        $this->expectException(QueryException::class);
        $borrowBook->save();
    }

    /**
     * Test that the timestamp fields in the BorrowBook model can be nullable.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrow book with the provided member and user IDs,
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

        $borrowBook = BorrowBook::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
            'borrow_date' => null,
            'return_date' => null,
            'returned_date' => null,
        ]);

        $this->assertNull($borrowBook->borrow_date);
        $this->assertNull($borrowBook->return_date);
        $this->assertNull($borrowBook->returned_date);
    }

    /**
     * Test that the borrow number must be unique.
     *
     * This function expects a QueryException to be thrown when attempting to create
     * two records with the same borrow number. It creates a new employee, user,
     * and member using factories, and then creates a borrow book with a unique
     * borrow number. It then attempts to create another record with the same
     * borrow number, which should result in a QueryException.
     *
     * @throws QueryException If attempting to create two records with the same borrow number.
     * @return void
     */
    public function testBorrowNumberMustBeUnique(): void
    {
        $this->expectException(QueryException::class);

        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        BorrowBook::factory()->create([
            'borrow_number' => 'unique_number',
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        // Attempt to create another record with the same borrow_number
        BorrowBook::factory()->create([
            'borrow_number' => 'unique_number',
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test that the default status of a borrow book is 'borrowed'.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrow book with a random borrow number, borrow date,
     * return date, and quantity. The member and user IDs are set to the newly created
     * member and user, respectively. The returned borrow book is then found using
     * its ID and its status is asserted to be 'borrowed'.
     *
     * @return void
     */
    public function testDefaultStatusIsBorrowed(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        $borrowBook = BorrowBook::create([
            'borrow_number' => 'BN' . mt_rand(100000000, 999999999),
            'borrow_date' => Carbon::now()->subDays(mt_rand(1, 30)),
            'return_date' => Carbon::now()->addDays(mt_rand(1, 30)),
            'returned_date' => null,
            'quantity' => mt_rand(1,3),
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);
        $borrowBookFind = BorrowBook::find($borrowBook->id);

        $this->assertEquals('borrowed', $borrowBookFind->status);
    }

    /**
     * Test that a borrow book belongs to a member.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrow book with the provided member and user IDs.
     * Finally, it asserts that the created borrow book belongs to the member
     * and that the member IDs match.
     *
     * @return void
     */
    public function testBorrowBookBelongsToMember(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        $borrowBook = BorrowBook::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Member::class, $borrowBook->member);
        $this->assertEquals($member->id, $borrowBook->member->id);
    }

    /**
     * Test that a borrow book belongs to a user.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrowbook with the provided member and user IDs.
     * Finally, it asserts that the created borrow book belongs to the user
     * and that the user IDs match.
     *
     * @return void
     */
    public function testBorrowBookBelongsToUser(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        $borrowBook = BorrowBook::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $borrowBook->user);
        $this->assertEquals($user->id, $borrowBook->user->id);
    }

    /**
     * Test that a borrow book belongs to many books.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrow book with the provided member and user IDs.
     * Next, it creates two books with the same user ID.
     * The books are then attached to the borrow book.
     * The relationships are reloaded.
     * The function then tests the user and borrow book relationship,
     * the borrow book and books relationship,
     * and the reverse relationship.
     *
     * @return void
     */
    public function testBorrowBookBelongsToManyBooks(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);
        $borrowBook = BorrowBook::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);
        $book1 = Book::factory()->create(['user_id' => $user->id]);
        $book2 = Book::factory()->create(['user_id' => $user->id]);

        // Attach books to the borrow book
        $borrowBook->books()->attach([$book1->id, $book2->id]);

        // Reload relationships
        $borrowBook = $borrowBook->fresh();

        // Test user and borrow book relationship
        $this->assertInstanceOf(User::class, $borrowBook->user);
        $this->assertEquals($user->id, $borrowBook->user->id);

        // Test borrow book and books relationship
        $this->assertTrue($borrowBook->books->contains($book1));
        $this->assertTrue($borrowBook->books->contains($book2));

        // Test reverse relationship
        $this->assertTrue($book1->borrowBooks->contains($borrowBook));
        $this->assertTrue($book2->borrowBooks->contains($borrowBook));
    }

    /**
     * Test the soft delete functionality of the BorrowngBook model.
     *
     * This function creates a new employee, user, and member using factories.
     * It then creates a borrow book with the provided member and user IDs.
     * The borrow book is soft deleted and the function asserts that the
     * borrow book is soft deleted in the 'borrow_books' table.
     *
     * @return void
     */
    public function testBorrowBookSoftDelete(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        $borrowBook = BorrowBook::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        $borrowBook->delete();

        $this->assertSoftDeleted('borrow_books', [
            'id' => $borrowBook->id,
        ]);
    }
}
