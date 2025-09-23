<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Referral>
 */
class ReferralFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commission_rate' => fake()->randomFloat(2, 1, 15), // 1-15% commission
            'commission_earned' => fake()->randomFloat(2, 0, 500),
            'clicks' => fake()->numberBetween(0, 1000),
            'conversions' => fake()->numberBetween(0, 50),
            'is_active' => fake()->boolean(90),
        ];
    }
}
