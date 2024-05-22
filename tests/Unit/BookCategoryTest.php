<?php

namespace Tests\Unit;

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
    * @var array
    */
   private array $requiredFields = [  
    'category_name',
    'description',
    'user_id',
];

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

    public function testRequiredFields(): void
    {
        $bookCategory = new BookCategory();

        foreach ($this->requiredFields as $field) {
            $bookCategory->{$field} = null;
        }
 
        $this->expectException(QueryException::class);
        $bookCategory->save();
    }

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

    public function testBookCategoryBelongsToUser(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookCategory = BookCategory::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $bookCategory->user);
        $this->assertEquals($user->id, $bookCategory->user->id);
    }

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

    public function testBookCategorySoftDeletes(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $bookCategory = BookCategory::factory()->create(['user_id'=> $user->id]);

        $bookCategory->delete();

        $this->assertSoftDeleted('book_categories', ['id' => $bookCategory->id]);
    }
}
