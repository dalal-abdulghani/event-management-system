<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketType>
 */
class TicketTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalQuantity = fake()->numberBetween(50, 1000);
        
        return [
            'name' => fake()->randomElement(['General Admission', 'VIP', 'Premium', 'Early Bird', 'Student']),
            'description' => fake()->optional()->sentence(),
            'price' => fake()->randomFloat(2, 10, 500),
            'quantity_total' => $totalQuantity,
            'quantity_available' => fake()->numberBetween(0, $totalQuantity),
            'sale_start' => fake()->optional()->dateTimeBetween('-2 months', 'now'),
            'sale_end' => fake()->optional()->dateTimeBetween('now', '+6 months'),
            'min_purchase' => 1,
            'max_purchase' => fake()->numberBetween(5, 20),
            'is_active' => fake()->boolean(90),
            'benefits' => fake()->optional()->randomElements([
                'priority_seating', 'complimentary_drinks', 'meet_and_greet',
                'exclusive_access', 'parking_included', 'merchandise'
            ], fake()->numberBetween(1, 3)),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
