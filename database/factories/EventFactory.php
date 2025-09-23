<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $eventTypes = [
            'Tech Conference',
            'Music Festival',
            'Business Summit',
            'Art Exhibition',
            'Food Festival',
            'Sports Tournament',
            'Workshop Series',
            'Networking Event',
            'Product Launch',
            'Charity Gala',
        ];

        $title = fake()->randomElement($eventTypes) . ' ' . fake()->year();
        $startDate = fake()->dateTimeBetween('+1 week', '+6 months');
        $endDate = fake()->dateTimeBetween($startDate, $startDate->format('Y-m-d H:i:s') . ' +7 days');
        $registrationStart = fake()->dateTimeBetween('-2 months', $startDate->format('Y-m-d H:i:s') . ' -1 week');
        $registrationEnd = fake()->dateTimeBetween($registrationStart, $startDate);

        $minPrice = fake()->randomFloat(2, 10, 100);
        $maxPrice = fake()->randomFloat(2, $minPrice, 500);

        return [
            'title' => $title,
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(20),
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'venue_id' => Venue::factory(),
            'organizer_id' => User::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'registration_start' => $registrationStart,
            'registration_end' => $registrationEnd,
            'status' => fake()->randomElement(['draft', 'published', 'published', 'published']), // weighted towards published
            'images' => [
                fake()->imageUrl(800, 600, 'events'),
                fake()->imageUrl(800, 600, 'events'),
                fake()->imageUrl(800, 600, 'events'),
            ],
            'cancellation_policy' => fake()->paragraph(2),
            'additional_info' => [
                'dress_code' => fake()->randomElement(['casual', 'business_casual', 'formal', 'none']),
                'age_restriction' => fake()->randomElement(['none', '18+', '21+', 'family_friendly']),
                'parking' => fake()->boolean(70),
                'food_provided' => fake()->boolean(60),
            ],
            'is_featured' => fake()->boolean(20),
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'total_capacity' => $capacity = fake()->numberBetween(50, 2000),
            'available_capacity' => fake()->numberBetween(0, $capacity),
            'tags' => fake()->randomElements([
                'technology', 'business', 'music', 'art', 'food', 'sports',
                'networking', 'education', 'entertainment', 'health', 'fashion',
                'travel', 'gaming', 'startup', 'marketing', 'design'
            ], fake()->numberBetween(2, 5)),
        ];
    }
}
