<?php
// tests/Unit/MemberTest.php

namespace Tests\Unit;

use App\Models\Employee;
use Tests\TestCase;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;

class MemberTest extends TestCase
{
    use RefreshDatabase;
    /**
    * An array of required fields for the Member model.
    *
    * @var array
    */
    private array $requiredFields = [  
        'number_card',
        'full_name',
        'email',
        'phone_number',
        'address',
        'image_name',
        'user_id',
    ];

    /**
     * Test the creation of a member.
     *
     * This test case creates a new member using the `Member::factory()->create()` method
     * and asserts that the created member is an instance of the `Member` class. It also
     * asserts that the member's email exists in the 'members' table of the database.
     *
     * @return void
     */
    public function testMemberCreation() :void
    {
        $employee = Employee::factory()->create();
        
        $user = User::factory()->create([
            'employee_id' => $employee->id
        ]);

        $member = Member::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Member::class, $member);
        $this->assertDatabaseHas('members', [
            'email' => $member->email,
        ]);
    }

    /**
     * Test that required fields for Member model are not nullable.
     *
     * This function creates a new instance of the Member model and sets each of the
     * required fields to null. It then attempts to save the model, expecting a
     * QueryException to be thrown due to the null values.
     *
     * @throws QueryException if any of the required fields are null
     * @return void
     */
    public function testRequiredFields() :void
    {
        $member = new Member();
 
        foreach ($this->requiredFields as $field) {
            $member->{$field} = null;
        }
 
        $this->expectException(QueryException::class);
        $member->save();
    }

    /**
     * Test that the number_card is unique.
     *
     * This test case verifies that the number_card field must be unique for each member.
     * It creates two users and then attempts to create two members with the same number_card.
     * The test expects a QueryException to be thrown, indicating that the number_card is not unique.
     *
     * @return void
     */
    public function testNumberCardMustBeUnique() :void
    {
        $this->expectException(QueryException::class);

        // $employee = Employee::factory()->create();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Member::factory()->create([
            'number_card' => 'CARD-1234',
            'user_id' => $user1->id,
        ]);

        // Attempt to create another member with the same number_card
        Member::factory()->create([
            'number_card' => 'CARD-1234',
            'user_id' => $user2->id,
        ]);
    }


    /**
     * Test that the email is unique.
     *
     * This test case verifies that the email field must be unique for each member.
     * It creates two users and then attempts to create two members with the same email.
     * The test expects a QueryException to be thrown, indicating that the email is not unique.
     *
     * @throws QueryException if the email is not unique
     * @return void
     */
    public function testEmailMustBeUnique() :void
    {
        $this->expectException(QueryException::class);

        // $employee = Employee::factory()->create();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Member::factory()->create([
            'email' => 'unique@example.com',
            'user_id' => $user1->id,
        ]);

        // Attempt to create another member with the same email
        Member::factory()->create([
            'email' => 'unique@example.com',
            'user_id' => $user2->id,
        ]);
    }

    /**
     * Test that the phone_number is unique.
     *
     * This test case verifies that the phone_number field must be unique for each member.
     * It creates two users and then attempts to create two members with the same phone_number.
     * The test expects a QueryException to be thrown, indicating that the phone_number is not unique.
     *
     * @throws QueryException if the phone_number is not unique
     * @return void
     */
    public function testPhoneNumberMustBeUnique() :void
    {
        $this->expectException(QueryException::class);

        // $employee = Employee::factory()->create();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Member::factory()->create([
            'phone_number' => '123-456-7890',
            'user_id' => $user1->id,
        ]);

        // Attempt to create another member with the same phone_number
        Member::factory()->create([
            'phone_number' => '123-456-7890',
            'user_id' => $user2->id,
        ]);
    }

    /**
     * Test that a member belongs to a user.
     *
     * This test case creates an employee, a user associated with the employee,
     * and a member associated with the user. It then asserts that the member
     * belongs to the user by checking if the member's user is an instance of
     * the User class and if the user's ID matches the member's user ID.
     *
     * @return void
     */
    public function testMemberBelongsToUser() :void
    {
        $employee = Employee::factory()->create();
        $user = User::factory()->create([
            'employee_id' => $employee->id 
        ]);

        $member = Member::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $member->user);
        $this->assertEquals($user->id, $member->user->id);
    }

    /**
     * Test the soft deletion of a member .
     *
     * This test creates an member using the factory method, then deletes the employee.
     * It asserts that the member is soft deleted by checking if the member's record
     * is present in the 'members' table with a deleted_at timestamp.
     * 
     * @return void
     */
    public function testMemberSoftDelete() :void
    {

        $employee = Employee::factory()->create();
        $user = User::factory()->create([
            'employee_id' => $employee->id
        ]);

        $member = Member::factory()->create([
            'user_id' => $user->id,
        ]);

       

        $member->delete();

        $this->assertSoftDeleted('members', [
            'id' => $member->id,
        ]);
    }
}

