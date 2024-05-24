<?php

namespace Tests\Feature\Models;

use App\Models\Book;
use App\Models\Bookshelf;
use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookshelfTest extends TestCase
{
    use RefreshDatabase;

    /**
    * An array of required fields for the Bookshelf model.
    *
    * @var array
    */
    private array $requiredFields = [  
        'bookshelf_number',
        'location',
        'user_id',
    ];

    /**
     * Test the creation of a bookshelf.
     *
     * This function tests the creation of a bookshelf by creating a new employee,
     * a new user associated with the employee, and a new bookshelf associated with
     * the user. It then asserts that the bookshelf is an instance of the Bookshelf
     * class and that it exists in the 'bookshelves' table in the database.
     *
     * @return void
     */
    public function testBookshelfCreation() : void 
    {
        $employee = Employee::factory()->create();

        $user = User::factory()->create(['employee_id' => $employee->id]);

        $bookshelf = Bookshelf::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Bookshelf::class, $bookshelf);
        $this->assertDatabaseHas('bookshelves', [
            'id' => $bookshelf->id,
        ]);
    }

    /**
     * Test that all required fields for the Bookshelf model are required.
     *
     * This function creates a new Bookshelf instance and sets all required fields to null.
     * It then attempts to save the instance, which should result in a QueryException
     * due to the required fields being null.
     *
     * @throws QueryException If any required field is null.
     * @return void
     */
    public function testRequiredFields(): void
    {
        $bookshelf = new Bookshelf();

        foreach ($this->requiredFields as $field) {
            $bookshelf->{$field} = null;
        }
 
        $this->expectException(QueryException::class);
        $bookshelf->save();
    }

    /**
     * Test if the bookshelf number is unique.
     *
     * This function creates a new employee, a new user associated with the employee, and a new bookshelf associated with the user.
     * It then asserts that the bookshelf is an instance of the Bookshelf class and that it exists in the 'bookshelves' table in the database.
     *
     * @throws QueryException If the bookshelf number is not unique.
     * @return void
     */
    public function testBookshelfNumberIsUnique(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);

        Bookshelf::factory()->create([
            'bookshelf_number' => 'A1',
            'user_id' => $user->id,
        ]);

        $this->expectException(QueryException::class);

        Bookshelf::factory()->create([
            'bookshelf_number' => 'A1',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test if the bookshelf belongs to the user.
     *
     * This function creates a new employee, a new user associated with the employee, and a new bookshelf associated with the user.
     * It then asserts that the bookshelf belongs to the user by checking if the user instance is of the User class and if the user ID matches the bookshelf's user ID.
     *
     * @return void
     */
    public function testBookshelfBelongsToUser(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookshelf = Bookshelf::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $bookshelf->user);
        $this->assertEquals($user->id, $bookshelf->user->id);
    }

    /**
     * Test if the bookshelf belongs to many books.
     *
     * This function creates a new employee, a new user associated with the employee,
     * and a new bookshelf associated with the user. It then creates two books
     * associated with the same user. The bookshelf is then attached to these books
     * using the 'books' relationship. Finally, the function asserts that the first
     * book in the bookshelf's books collection is an instance of the Book class,
     * and that the bookshelf contains both books.
     *
     * @return void
     */
    public function testBookshelfBelongsToManyBooks(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookshelf = Bookshelf::factory()->create(['user_id' => $user->id]);

        $book1 = Book::factory()->create(['user_id' => $user->id]);
        $book2 = Book::factory()->create(['user_id' => $user->id]);

        $bookshelf->books()->attach([$book1->id,$book2->id]);

        $bookshelf = $bookshelf->fresh();

        $this->assertInstanceOf(Book::class, $bookshelf->books->first());
        $this->assertTrue($bookshelf->books->contains($book1));
        $this->assertTrue($bookshelf->books->contains($book2));
        
    }

    /**
     * Test the soft deletion of a bookshelf.
     *
     * This function creates a new employee, a new user associated with the employee,
     * and a new bookshelf associated with the user. It then soft deletes the bookshelf
     * and asserts that the bookshelf is soft deleted by checking if it exists in the
     * 'bookshelves' table with the given ID.
     *
     * @return void
     */
    public function testBookshelfSoftDeletes(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookshelf = Bookshelf::factory()->create(['user_id'=> $user->id]);

        $bookshelf->delete();

        $this->assertSoftDeleted('bookshelves', ['id' => $bookshelf->id]);
    }


}
