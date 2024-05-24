<?php
namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * The required fields for an Employee model.
     * 
     * @var array
     */
    private array $requiredFields = [  
        'full_name',
        'email',
        'number_phone',
        'address',
        'nik',
        'quantity_now',
   ];
    /**
     * Test the creation of an employee.
     *
     * This test creates an employee using the factory method and asserts that the
     * employee is an instance of the Employee class and that the employee's full name
     * is present in the 'employees' table.
     *
     * @return void
     */
    public function testEmployeeCreation() :void
    {
        $employee = Employee::factory()->create();

        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertDatabaseHas('employees', [
            'full_name' => $employee->full_name,
        ]);
    }

    /**
     * Test that all required fields for the Employee model are required.
     *
     * This test creates a new Employee instance and sets all required fields to null.
     * It then attempts to save the instance, which should result in a QueryException
     * due to the required fields being null.
     *
     * @throws QueryException If any required field is null.
     * @return void
     */
    public function testRequiredFields() :void
    {
        $employee = new Employee();
 
        foreach ($this->requiredFields as $field) {
            $employee->{$field} = null;
        }
 
        $this->expectException(QueryException::class);
        $employee->save();
    }

    /**
     * Test that an email must be unique.
     *
     * This test attempts to create two employees with the same email address,
     * which should result in a QueryException due to the unique constraint on the
     * email column.
     *
     * @throws QueryException If an employee with the same email already exists.
     * @return void
     */
    public function testEmailMustBeUnique() : void
    {
        $this->expectException(QueryException::class);

        Employee::factory()->create([
            'email' => 'unique@example.com',
        ]);

        // Attempt to create another employee with the same email
        Employee::factory()->create([
            'email' => 'unique@example.com',
        ]);
    }

    /**
     * Test that NIK (National Identification Number) must be unique.
     *
     * This test attempts to create two employees with the same NIK, which should
     * result in a QueryException due to the unique constraint on the NIK column.
     *
     * @throws QueryException If an employee with the same NIK already exists.
     * @return void
     */
    public function testNIKMustBeUnique() :void
    {
        $this->expectException(QueryException::class);

        Employee::factory()->create([
            'nik' => '1234567890',
        ]);

        // Attempt to create another employee with the same NIK
        Employee::factory()->create([
            'nik' => '1234567890',
        ]);
    }

    /**
     * Test the soft deletion of an employee.
     *
     * This test creates an employee using the factory method, then deletes the employee.
     * It asserts that the employee is soft deleted by checking if the employee's record
     * is present in the 'employees' table with a deleted_at timestamp.
     *
     * @return void
     */
    public function testEmployeeSoftDelete() :void
    {
        $employee = Employee::factory()->create();

        $employee->delete();

        $this->assertSoftDeleted('employees', [
            'id' => $employee->id,
        ]);
    }
}
