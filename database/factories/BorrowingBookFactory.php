<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BorrowingBook>
 */
class BorrowingBookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'borrowing_number' => $this->faker->unique()->numerify('BN#########'),
            'member_id' => $this->faker->numberBetween(1, 10),
            'borrow_date' => $this->faker->dateTime(),
            'return_date' => $this->faker->dateTime(),
            'returned_date' => $this->faker->dateTime(),
            'quantity' => $this->faker->randomNumber(),
            'status' => $this->faker->randomElement(['borrowed', 'due', 'returned']),
            'user_id' => 3,
        ];
    }
}
