<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fine>
 */
class FineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fine_number' => $this->faker->unique()->numerify('FC##########'),
            'member_id'=> $this->faker->numberBetween(1, 10),
            'non_member_name' => null,
            'amount' => $this->faker->randomFloat(2,1,1000),
            'amount_paid' => 0,
            'change_amount' => 0,
            'reason' => $this->faker->text(),
            'charged_at' => $this->faker->dateTime('now'),
            'is_paid' => false,
            'user_id' => 3,
        ];
    }
}
