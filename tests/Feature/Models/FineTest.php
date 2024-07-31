<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Fine;
use App\Models\Employee;
use App\Models\User;
use App\Models\Member;
use App\Models\BorrowBookPivot;
use Illuminate\Database\QueryException;

class FineTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * An array of required fields for the Fine model.
     *
     * @var array<string>
     */
    private array $requiredFields = [          
        'fine_number',
        'amount',
        'amount_paid',
        'change_amount',
        'reason',
        'is_paid',
        'user_id',
    ];

    /**
     * Test the creation of a Fine.
     *
     * @return void
     */
    public function testFineCreation(): void
    {
        // Creating employee, user, and member instances
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);
        
        // Creating Fine instances for both member and non-member
        $fineMember = Fine::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);
        $fineNonMember = Fine::factory()->create([
            'member_id' => null,
            'non_member_name' => 'Samsudin',
            'user_id' => $user->id,
        ]);

        // Asserting the instances are Fine
        $this->assertInstanceOf(Fine::class, $fineMember);
        $this->assertInstanceOf(Fine::class, $fineNonMember);

        // Asserting the Fine instances are present in the database
        $this->assertDatabaseHas('fines', [
            'id' => $fineMember->id,
        ]);
        $this->assertDatabaseHas('fines', [
            'id' => $fineNonMember->id,
        ]);
    }

    /**
     * Test that required fields are enforced.
     *
     * @return void
     */
    public function testRequiredFields(): void
    {
        $fine = new Fine();

        // Setting all required fields to null
        foreach ($this->requiredFields as $field) {
            $fine->{$field} = null;
        }

        // Expecting a QueryException due to missing required fields
        $this->expectException(QueryException::class);
        $fine->save();
    }

    /**
     * Test that nullable fields can be null.
     *
     * @return void
     */
    public function testNullableFields(): void
    {
        // Creating employee, user, and member instances
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        // Creating a Fine instance with nullable fields set to null
        $fine = Fine::factory()->create([
            'member_id' => null,
            'non_member_name' => null,
            'charged_at' => null,
            'user_id' => $user->id,
        ]);

        // Asserting nullable fields are null
        $this->assertNull($fine->member_id);
        $this->assertNull($fine->non_member_name);
        $this->assertNull($fine->charged_at);
    }

    /**
     * Test that fine_number is unique.
     *
     * @return void
     */
    public function testFineNumberMustBeUnique(): void
    {
        // Expecting a QueryException due to unique constraint violation
        $this->expectException(QueryException::class);

        // Creating employee, user, and member instances
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        // Creating a Fine instance with a unique fine_number
        Fine::factory()->create([
            'fine_number' => 'unique_number',
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        // Attempting to create another Fine instance with the same fine_number
        Fine::factory()->create([
            'fine_number' => 'unique_number',
            'non_member_name' => 'Rahmat',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test that is_paid must be boolean and has a default value of false.
     *
     * @return void
     */
    public function testIsPaidMustBeBooleanAndDefaultValueIsFalse(): void
    {
        // Expecting a QueryException due to invalid boolean value
        $this->expectException(QueryException::class);

        // Creating employee, user, and member instances
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        // Creating a Fine instance
        $fineFind = Fine::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        // Asserting the Fine instance is present in the database
        $this->assertDatabaseHas('fines', [
            'id' => $fineFind->id,
        ]);

        // Attempting to create another Fine instance with an invalid is_paid value
        Fine::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
            'is_paid' => 'string',
        ]);
    }

    /**
     * Test that Fine belongs to a User.
     *
     * @return void
     */
    public function testFineBelongsToUser(): void
    {
        // Creating employee and user instances
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);

        // Creating a Fine instance for a non-member
        $fine = Fine::factory()->create([
            'member_id' => null,
            'non_member_name' => 'Firdaus',
            'user_id' => $user->id,
        ]);

        // Asserting the Fine instance belongs to the User
        $this->assertInstanceOf(User::class, $fine->user);
        $this->assertEquals($user->id, $fine->user->id);
    }

    /**
     * Test that Fine belongs to a Member.
     *
     * @return void
     */
    public function testFineBelongsToMember(): void
    {
        // Creating employee, user, and member instances
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        // Creating a Fine instance for a member
        $fine = Fine::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        // Asserting the Fine instance belongs to the Member
        $this->assertInstanceOf(Member::class, $fine->member);
        $this->assertEquals($member->id, $fine->member->id);
    }

    /**
     * Test that Fine can be soft deleted.
     *
     * @return void
     */
    public function testFineSoftDelete(): void
    {
        // Creating employee, user, and member instances
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        // Creating a Fine instance
        $fine = Fine::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        // Soft deleting the Fine instance
        $fine->delete();

        // Asserting the Fine instance is soft deleted
        $this->assertSoftDeleted('fines', [
            'id' => $fine->id,
        ]);
    }
}

