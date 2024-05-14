<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(10, 100);
        return [
            'isbn' => $this->faker->isbn13,
            'title' => $this->faker->sentence(4),
            'cover_image_name' => 'default.jpg',
            'published_year' => $this->faker->year,
            'price_per_book' => $this->faker->randomFloat(2, 10, 100),
            'quantity' => $quantity,
            'quantity_now' => $quantity, 
            'description' => $this->faker->paragraphs(3, true),
            'user_id' => 4, 
        ];
    }
}
