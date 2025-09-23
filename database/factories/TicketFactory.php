<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->randomElement(['valid', 'used', 'cancelled', 'refunded']),
            'additional_info' => fake()->optional()->randomElements([
                'dietary_requirements' => fake()->randomElement(['vegetarian', 'vegan', 'gluten_free', 'none']),
                'special_needs' => fake()->optional()->sentence(),
                'emergency_contact' => fake()->phoneNumber(),
            ]),
        ];
    }
}
