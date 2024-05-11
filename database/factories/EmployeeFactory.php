<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'number_phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'nik' => $this->faker->unique()->randomNumber(7, true), // Assuming NIK is 7 digits
        ];
    }
}
