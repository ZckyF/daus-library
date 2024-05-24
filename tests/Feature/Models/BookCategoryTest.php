<?php

namespace Tests\Feature\Models;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class BookCategoryTest extends TestCase
{
    use RefreshDatabase;
    
   /**
    * An array of required fields for the BookCategory model.
    *
    * @var array<string>
    */
   private array $requiredFields = [  
    'category_name',
    'description',
    'user_id',
];

    /**
     * Test the creation of a BookCategory model.
     *
     * This test creates a new Employee model, a new User model with the Employee
     * model as its employee_id, and a new BookCategory model with the User model
     * as its user_id. It then asserts that the BookCategory model is an instance of
     * the BookCategory class and that the BookCategory model is present in the
     * 'book_categories' database table.
     *
     * @return void
     */
    public function testBookCategoryCreation(): void
    {
        $employee = Employee::factory()->create();

        $user = User::factory()->create(['employee_id' => $employee->id]);

        $bookCategory = BookCategory::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(BookCategory::class, $bookCategory);
        $this->assertDatabaseHas('book_categories', [
            'id' => $bookCategory->id,
        ]);
    }

    /**
     * Test the required fields of the BookCategory model.
     *
     * This function creates a new instance of the BookCategory model and sets each
     * required field to null. It then expects a QueryException to be thrown when
     * the model is saved.
     *
     * @throws QueryException If any of the required fields are not set
     * @return void
     */
    public function testRequiredFields(): void
    {
        $bookCategory = new BookCategory();

        foreach ($this->requiredFields as $field) {
            $bookCategory->{$field} = null;
        }
 
        $this->expectException(QueryException::class);
        $bookCategory->save();
    }

    /**
     * Test that the category name must be unique for each user.
     *
     * This function creates a new Employee model, a new User model with the Employee
     * model as its employee_id, and a new BookCategory model with the User model
     * as its user_id. It then creates another BookCategory model with the same
     * category name and user_id, which should throw a QueryException.
     *
     * @throws QueryException If the category name is not unique for the user
     * @return void
     */
    public function testCategoryNameIsUnique(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);

        BookCategory::factory()->create([
            'category_name' => 'UniqueCategory',
            'user_id' => $user->id,
        ]);

        $this->expectException(QueryException::class);

        BookCategory::factory()->create([
            'category_name' => 'UniqueCategory',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test that a BookCategory belongs to a User.
     *
     * This function creates a new Employee model, a new User model with the Employee
     * model as its employee_id, and a new BookCategory model with the User model
     * as its user_id. It then asserts that the BookCategory model belongs to the
     * User model and that the user_id of the BookCategory model is equal to the
     * id of the User model.
     *
     * @return void
     */
    public function testBookCategoryBelongsToUser(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookCategory = BookCategory::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $bookCategory->user);
        $this->assertEquals($user->id, $bookCategory->user->id);
    }

    /**
     * Test that a BookCategory belongs to many Books.
     *
     * This function creates a new Employee model, a new User model with the Employee
     * model as its employee_id, and a new BookCategory model with the User model
     * as its user_id. It then creates two Book models with the same user_id and
     * attaches them to the BookCategory model. Finally, it asserts that the
     * BookCategory model belongs to the first and second Book models.
     *
     * @return void
     */
    public function testBookCategoryBelongsToManyBooks(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookCategory = BookCategory::factory()->create(['user_id' => $user->id]);

        $book1 = Book::factory()->create(['user_id' => $user->id]);
        $book2 = Book::factory()->create(['user_id' => $user->id]);

        $bookCategory->books()->attach([$book1->id,$book2->id]);

        $bookCategory = $bookCategory->fresh();

        $this->assertInstanceOf(Book::class, $bookCategory->books->first());
        $this->assertTrue($bookCategory->books->contains($book1));
        $this->assertTrue($bookCategory->books->contains($book2));
        
    }

    /**
     * Test the soft deletion of a BookCategory model.
     *
     * This test creates a new Employee model, a new User model with the Employee
     * model as its employee_id, and a new BookCategory model with the User model
     * as its user_id. It then deletes the BookCategory model and asserts that it
     * has been soft deleted by checking that it is present in the 'book_categories'
     * database table with a deleted_at timestamp.
     *
     * @return void
     */
    public function testBookCategorySoftDeletes(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookCategory = BookCategory::factory()->create(['user_id'=> $user->id]);

        $bookCategory->delete();

        $this->assertSoftDeleted('book_categories', ['id' => $bookCategory->id]);
    }
}
