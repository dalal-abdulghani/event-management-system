<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['percentage', 'fixed']);
        $value = $type === 'percentage' ? fake()->numberBetween(5, 50) : fake()->randomFloat(2, 5, 100);
        
        return [
            'code' => strtoupper(fake()->lexify('??????')),
            'name' => fake()->words(3, true) . ' Discount',
            'description' => fake()->sentence(),
            'type' => $type,
            'value' => $value,
            'minimum_amount' => fake()->optional()->randomFloat(2, 50, 200),
            'usage_limit' => fake()->optional()->numberBetween(10, 1000),
            'usage_limit_per_user' => fake()->optional()->numberBetween(1, 5),
            'used_count' => 0,
            'valid_from' => fake()->dateTimeBetween('-1 month', 'now'),
            'valid_until' => fake()->dateTimeBetween('+1 week', '+6 months'),
            'is_active' => fake()->boolean(80),
            'applicable_events' => fake()->optional()->randomElements([1, 2, 3, 4, 5], fake()->numberBetween(1, 3)),
        ];
    }
}
