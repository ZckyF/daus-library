<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\Employee;
use App\Models\Fine;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FineTest extends TestCase
{
    use RefreshDatabase;
    
   /**
    * An array of required fields for the Book model.
    *
    * @var array<string>
    */
    private array $requiredFields = [          
        'fine_code',
        'amount',
        'amount_paid',
        'change_amount',
        'reason',
        'is_paid',
        'user_id',
    ];

    public function testFineCreation(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);
        
        // if it is a member
        $fineMember = Fine::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);
        // if it is not a member
        $fineNonMember = Fine::factory()->create([
            'member_id' => null,
            'non_member_name' => 'Samsudin',
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Fine::class, $fineMember);
        $this->assertInstanceOf(Fine::class, $fineNonMember);

        $this->assertDatabaseHas('fines', [
            'id' => $fineMember->id,
        ]);
        $this->assertDatabaseHas('fines', [
            'id' => $fineNonMember->id,
        ]);
    }

    public function testRequiredFields(): void
    {
        $fine = new Fine();

        foreach ($this->requiredFields as $field) {
            $fine->{$field} = null;
        }

        $this->expectException(QueryException::class);
        $fine->save();
    }

    public function testNullableFields(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        $fine = Fine::factory()->create([
            'member_id' => null,
            'non_member_name' => null,
            // In later cases, one of the columns above must be filled in to save      
            'charged_at' => null,
            'user_id' => $user->id,

        ]);

        $this->assertNull($fine->member_id);
        $this->assertNull($fine->non_member_name);
        $this->assertNull($fine->charged_at);
    }

    public function testFineCodeMustBeUnique(): void
    {
        $this->expectException(QueryException::class);

        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        Fine::factory()->create([
            'fine_code' => 'unique_number',
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        // Attempt to create another record with the same fine_code
        Fine::factory()->create([
            'fine_code' => 'unique_number',
            'non_member_name' => 'Rahmat',
            'user_id' => $user->id,
        ]);
    }

    public function testIsPaidMustBeBooleanAndDefaultValueIsFalse(): void
    {
        $this->expectException(QueryException::class);

        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

       $fineFind = Fine::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('fines', [
            'id' => $fineFind->id,
        ]);

        
        Fine::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
            'is_paid' => 'string',
        ]);

    }

    public function testFineBelongsToUser(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);

        $fine = Fine::factory()->create([
            'member_id' => null,
            'non_member_name' => 'Firdaus',
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $fine->user);
        $this->assertEquals($user->id, $fine->user->id);
    }

    public function testFineBelongsToMember(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        $fine = Fine::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Member::class, $fine->member);
        $this->assertEquals($member->id, $fine->member->id);
    }

    
    public function testFineSoftDelete(): void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create(['employee_id' => $employee->id]);
        $member = Member::factory()->create(['user_id' => $user->id]);

        $fine = Fine::factory()->create([
            'member_id' => $member->id,
            'user_id' => $user->id,
        ]);

        $fine->delete();

        $this->assertSoftDeleted('fines', [
            'id' => $fine->id,
        ]);
    }
}
