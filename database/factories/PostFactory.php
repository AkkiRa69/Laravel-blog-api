<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'caption' => fake()->sentence(),
            'image' => fake()->randomElement([
                'https://randomuser.me/api/portraits/men/' . fake()->numberBetween(1, 99) . '.jpg',
                'https://randomuser.me/api/portraits/women/' . fake()->numberBetween(1, 99) . '.jpg'
            ]),
        ];
    }
}
