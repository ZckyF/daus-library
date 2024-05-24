<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Employee;
use App\Models\Bookshelf;
use App\Models\BookCategory;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class BookTest extends TestCase
{
   use RefreshDatabase;

   /**
    * An array of required fields for the Book model.
    *
    * @var array<string>
    */
   private array $requiredFields = [  
        'isbn',
        'title',
        'published_year',
        'price_per_book',
        'quantity',
        'quantity_now',
        'description',
        'user_id',
   ];

    /**
     * Test the creation of a book.
     *
     * This test creates an employee, a user, and a book, and then asserts that the book
     * is an instance of the Book class and that the book exists in the 'books' table
     * with the correct id.
     *
     * @return void
     */ 
    public function testBookCreation() :void
    {
         $employee = Employee::factory()->create();
 
        $user = User::factory()->create([
         'employee_id' => $employee->id
        ]);
 
        $book = Book::factory()->create([
            'user_id' => $user->id,
        ]);
 
        $this->assertInstanceOf(Book::class, $book);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
        ]);
    }  

    /**
     * Test that all required fields for the Book model are set.
     *
     * This function creates a new Book instance and sets all required fields to null.
     * It then attempts to save the Book instance and expects a QueryException to be thrown
     * because the required fields are not set.
     *
     * @throws QueryException If any of the required fields are not set
     * @return void
     */
   public function testRequiredFields() :void
   {
       $book = new Book();

       foreach ($this->requiredFields as $field) {
           $book->{$field} = null;
       }

       $this->expectException(QueryException::class);
       $book->save();
   }

    /**
     * Test that the cover_image_name field in the Book model is nullable.
     *
     * This test creates an employee, a user, and a book, and then asserts that the cover_image_name
     * field of the book is null.
     *
     * @return void
     */
   public function testCoverImageNameIsNullable() :void
   {
        $employee = Employee::factory()->create();

        $user = User::factory()->create([
            'employee_id' => $employee->id
        ]);
        $book = Book::factory()->create([
           'cover_image_name' => null,
           'user_id' => $user->id
        ]);

        $this->assertNull($book->cover_image_name);
   }

    /**
     * Test the validity of the 'price_per_book' field in the Book model.
     *
     * This function creates an employee, a user, and a book with a valid decimal value for the 'price_per_book' field.
     * It then asserts that the value of the 'price_per_book' field is equal to 99.99.
     * Next, it attempts to create another book with an invalid non-decimal value for the 'price_per_book' field and expects a QueryException to be thrown.
     *
     * @throws QueryException If the 'price_per_book' field is not a valid decimal value
     * @return void
     */
   public function testPricePerBookIsDecimal() :void
   {
        $employee = Employee::factory()->create();

        $user = User::factory()->create([
            'employee_id' => $employee->id
        ]);
       // Valid decimal value
        $book1 = Book::factory()->create([
            'price_per_book' => 99.99,
            'user_id' => $user->id
        ]);

        $this->assertEquals(99.99, $book1->price_per_book);

        // Invalid non-decimal value
        $this->expectException(QueryException::class);
        $book2 = Book::factory()->create([
            'price_per_book' => 'invalid',
            'user_id' => $user->id
        ]);
   }

    /**
     * Test the uniqueness of the ISBN field in the Book model.
     *
     * This test case verifies that the ISBN field in the Book model is unique,
     * meaning that it cannot have duplicate values for different books. It does
     * this by creating two users, creating a book with a specific ISBN for one
     * of the users, and then attempting to create another book with the same ISBN
     * for the other user. It expects that a QueryException will be thrown
     * because the ISBN is not unique.
     *
     * @throws QueryException If the ISBN is not unique
     * @return void
     */
   public function testIsbnMustBeUnique() :void
   {
       $this->expectException(QueryException::class);

    //    $employee = Employee::factory()->create();
    
       $user1 = User::factory()->create();
       $user2 = User::factory()->create();

       Book::factory()->create([
           'isbn' => '123-456-789',
           'user_id' => $user1->id,
       ]);

       // Attempt to create another book with the same isbn
       Book::factory()->create([
           'isbn' => '123-456-789',
           'user_id' => $user2->id,
       ]);
   }

    /**
     * Test that a book belongs to a user.
     *
     * This test creates a user and a book, and then asserts that the book belongs to the user.
     *
     * @return void
     */
   public function testBookBelongsToUser() :void
   {
       $employee = Employee::factory()->create();

       $user = User::factory()->create([
        'employee_id' => $employee->id
       ]);

       $book = Book::factory()->create([
           'user_id' => $user->id,
       ]);
       $this->assertInstanceOf(User::class, $book->user);
       $this->assertEquals($user->id, $book->user->id);
   }

    /**
     * Test that a book belongs to many book categories.
     *
     * This test creates an employee, a user, a book, and two book categories.
     * It attaches the book categories to the book and then reloads the book instance
     * to load the relationships. Finally, it asserts that the book is an instance
     * of the Book class, and that it contains the two book categories.
     *
     * @return void
     */
   public function testBookBelongsToManyBookCategories() :void
   {
       
       $employee = Employee::factory()->create();
       $user = User::factory()->create(['employee_id' => $employee->id]);

     
       $book = Book::factory()->create(['user_id' => $user->id]);

     
       $category1 = BookCategory::factory()->create(['user_id'=>$user->id]);
       $category2 = BookCategory::factory()->create(['user_id'=>$user->id]);

       // Attach categories to the book
       $book->bookCategories()->attach([$category1->id, $category2->id]);
       // Reload the book instance to load the relationships
       $book = $book->fresh();

      
       $this->assertInstanceOf(Book::class, $book);
       $this->assertTrue($book->bookCategories->contains($category1));
       $this->assertTrue($book->bookCategories->contains($category2));
   }

    /**
     * Test that a book belongs to many bookshelves.
     *
     * This test creates an employee, a user, a book, and two bookshelves.
     * It attaches the bookshelves to the book and then reloads the book instance
     * to load the relationships. Finally, it asserts that the book is an instance
     * of the Book class, and that it contains the two bookshelves.
     *
     * @return void
     */
   public function testBookBelongsToManyBookshelves() :void
   {
       $employee = Employee::factory()->create();
       $user = User::factory()->create(['employee_id' => $employee->id]);

       $book = Book::factory()->create(['user_id' => $user->id]);

       $shelf1 = Bookshelf::factory()->create(['user_id' => $user->id]);
       $shelf2 = Bookshelf::factory()->create(['user_id' => $user->id]);

       // Attach categories to the book
       $book->bookshelves()->attach([$shelf1->id, $shelf2->id]);

       // Refresh the book instance to get the latest relationships
       $book = $book->fresh();

       // Assert the relationships
       $this->assertInstanceOf(Bookshelf::class, $book->bookshelves->first());
       $this->assertTrue($book->bookshelves->contains($shelf1));
       $this->assertTrue($book->bookshelves->contains($shelf2));
   }

    /**
     * Test the soft delete functionality of the Book model.
     *
     * This test creates an employee, a user, and a book, and then deletes the book.
     * It asserts that the book is soft deleted by checking if it exists in the 'books' table
     * with the correct id.
     *
     * @return void
     */
   public function testBookSoftDelete() :void
   {

       $employee = Employee::factory()->create();

       $user = User::factory()->create([
        'employee_id' => $employee->id
       ]);

       $book = Book::factory()->create([
           'user_id' => $user->id,
       ]);

       $book->delete();

       $this->assertSoftDeleted('books', [
           'id' => $book->id,
       ]);
   }

}
