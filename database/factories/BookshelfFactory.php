<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookshelf>
 */
class BookshelfFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bookshelf_number' => $this->faker->unique()->numerify('BS###'),
            'location' => $this->faker->address,
            'user_id' => 4, 
        ];
    }
}
