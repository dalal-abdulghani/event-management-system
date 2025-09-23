<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sessionTypes = [
            'Keynote Speech', 'Panel Discussion', 'Workshop', 'Networking Break',
            'Product Demo', 'Q&A Session', 'Breakout Session', 'Presentation'
        ];
        
        $startTime = fake()->dateTimeBetween('+1 day', '+6 months');
        $endTime = fake()->dateTimeBetween($startTime, $startTime->format('Y-m-d H:i:s') . ' +4 hours');
        
        return [
            'title' => fake()->randomElement($sessionTypes) . ': ' . fake()->sentence(4),
            'description' => fake()->paragraphs(2, true),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'speaker_name' => fake()->name(),
            'speaker_bio' => fake()->paragraph(),
            'speaker_image' => fake()->imageUrl(300, 300, 'people'),
            'location' => fake()->randomElement(['Main Hall', 'Room A', 'Room B', 'Workshop Space', 'Auditorium']),
            'capacity' => fake()->optional()->numberBetween(20, 200),
            'additional_info' => [
                'materials_provided' => fake()->boolean(60),
                'recording_allowed' => fake()->boolean(70),
                'level' => fake()->randomElement(['beginner', 'intermediate', 'advanced']),
            ],
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }
}
