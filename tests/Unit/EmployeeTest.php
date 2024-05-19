<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the creation of an employee.
     *
     * This test creates an employee using the factory method and asserts that the
     * employee is an instance of the Employee class and that the employee's full name
     * is present in the 'employees' table.
     *
     * @return void
     */
    public function testEmployeeCreation()
    {
        $employee = Employee::factory()->create();

        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertDatabaseHas('employees', [
            'full_name' => $employee->full_name,
        ]);
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
    public function testEmailMustBeUnique()
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
    public function testNIKMustBeUnique()
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
    public function testEmployeeSoftDelete()
    {
        $employee = Employee::factory()->create();

        $employee->delete();

        $this->assertSoftDeleted('employees', [
            'id' => $employee->id,
        ]);
    }
}
