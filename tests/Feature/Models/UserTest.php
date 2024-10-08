<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;

class UserTest extends TestCase
{
    use RefreshDatabase;
     /**
     * The required fields for an Employee model.
     * 
     * @var array<string>
     */
    private array $requiredFields = [  
        'username',
        'password',
        'avatar_name',
        'is_active',
        'employee_id',
        'quantity_now',
    ];
    /**
     * Test the creation of a user with the given parameters.
     *
     * This test creates an employee using the factory method, then creates a user
     * with the specified parameters. It asserts that the user is an instance of
     * the User class and that the user's username is present in the 'users' table.
     *
     * @return void
     */
    public function testUserCreation() :void
    {
       

        $employee = Employee::factory()->create();

        $user = User::factory()->create([
            'employee_id' => $employee->id,
        ]);
        $this->assertInstanceOf(User::class, $user);

        $this->assertDatabaseHas('users', [
            'username' => $user->username,
        ]);
    }

    /**
     * Test that all required fields for the User model are required.
     *
     * This function creates a new User instance and sets all required fields to null.
     * It then attempts to save the user, which should result in a QueryException.
     *
     * @throws QueryException If any required field is null.
     * @return void
     */
    public function testRequiredFields() :void
    {
        $user = new User();
 
        foreach ($this->requiredFields as $field) {
            $user->{$field} = null;
        }
 
        $this->expectException(QueryException::class);
        $user->save();
    }

    /**
     * Test that the avatar image name is nullable.
     *
     * This function creates an employee using the factory method, then creates a user
     * with the specified parameters. It asserts that the avatar image name is null
     * by checking if the user's avatar_name attribute is null.
     *
     * @return void
     */
    public function testAvatarNameIsNullable() :void
    {
         $employee = Employee::factory()->create();
 
         $user = User::factory()->create([
             'employee_id' => $employee->id,
             'avatar_name' => null
         ]);
 
         $this->assertNull($user->avatar_name);
    }

    /**
     * Test the default value of the is_active field.
     *
     * This function creates an employee using the factory method, then creates a user
     * with the specified parameters. It saves the user and retrieves it from the database.
     * Finally, it asserts that the is_active field of the user is equal to 1.
     *
     * @return void
     */
    public function testIsActiveFieldDefaultIsTrue() :void 
    {
        $employee = Employee::factory()->create();
 
        $user = User::factory()->create([
            'employee_id' => $employee->id,
        ]);
         $user->save();

         $userFind = User::find($user->id);
        
        $this->assertEquals(1,$userFind->is_active);
    }

    /**
     * Test that a username must be unique.
     *
     * This test creates two employees using the factory method and then creates a user
     * with a unique username for each employee. It then attempts to create another user
     * with the same username for a different employee, which should result in a QueryException.
     *
     * @throws QueryException If a user with the same username already exists.
     * @return void
     */
    public function testUsernameMustBeUnique() :void
    {
        $this->expectException(QueryException::class);

        $employee1 = Employee::factory()->create();
        $employee2 = Employee::factory()->create();

        User::factory()->create([
            'username' => 'uniqueuser',
            'employee_id' => $employee1->id,
        ]);

        User::factory()->create([
            'username' => 'uniqueuser',
            'employee_id' => $employee2->id,
        ]);
    }

    /**
     * Test that a user belongs to an employee.
     *
     * This test creates an employee using the factory method, then creates a user
     * with the specified parameters. It asserts that the user belongs to the created
     * employee by checking if the user's employee relationship is an instance of
     * the Employee class and if the user's employee ID matches the created employee's ID.
     *
     * @return void
     */
    public function testUserBelongsToEmployee() :void
    {
        $employee = Employee::factory()->create();

        $user = User::factory()->create([
            'employee_id' => $employee->id,
        ]);

        $this->assertInstanceOf(Employee::class, $user->employee);
        $this->assertEquals($employee->id, $user->employee->id);
    }

    /**
     * Test the soft deletion of a user.
     *
     * This test creates a user using the factory method, then deletes the user.
     * It asserts that the user is soft deleted by checking if the user's record
     * is present in the 'users' table with a deleted_at timestamp.
     *
     * @return void
     */
    public function testUserSoftDelete() :void
    {

        $employee = Employee::factory()->create();

        $user = User::factory()->create([
            'employee_id' => $employee->id,
        ]);

        $user->delete();

        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    /**
     * Test the activation status of a user.
     *
     * This function creates a user with the 'is_active' attribute set to true,
     * asserts that the user is activated, sets the 'is_active' attribute to
     * false, saves the user, and finally asserts that the user is not activated.
     *
     * @return void
     */
    public function testUserIsActivated() :void
    {
        $employee = Employee::factory()->create();

        $user = User::factory()->create([
            'employee_id' => $employee->id,
        ]);

        $user->is_active = false;

        $user->save();

        $this->assertFalse($user->is_active);
    }
}

