<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 25, 500);
        $taxAmount = $subtotal * 0.08; // 8% tax
        $discountAmount = fake()->optional(0.3)->randomFloat(2, 5, 50) ?? 0;
        $totalAmount = $subtotal + $taxAmount - $discountAmount;
        
        return [
            'status' => fake()->randomElement(['pending', 'completed', 'failed', 'refunded', 'cancelled']),
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'payment_method' => fake()->randomElement(['stripe', 'paypal', 'credit_card']),
            'payment_intent_id' => fake()->optional()->regexify('pi_[A-Za-z0-9]{24}'),
            'currency' => 'USD',
            'billing_details' => [
                'name' => fake()->name(),
                'email' => fake()->email(),
                'address' => [
                    'line1' => fake()->streetAddress(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'postal_code' => fake()->postcode(),
                    'country' => 'US',
                ],
            ],
            'utm_data' => fake()->optional()->randomElements([
                'utm_source' => fake()->randomElement(['google', 'facebook', 'twitter', 'email']),
                'utm_medium' => fake()->randomElement(['cpc', 'organic', 'social', 'email']),
                'utm_campaign' => fake()->word(),
            ]),
        ];
    }
}
