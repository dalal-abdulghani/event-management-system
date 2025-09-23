<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Venue>
 */
class VenueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = [
            ['name' => 'New York', 'state' => 'NY', 'country' => 'USA'],
            ['name' => 'Los Angeles', 'state' => 'CA', 'country' => 'USA'],
            ['name' => 'Chicago', 'state' => 'IL', 'country' => 'USA'],
            ['name' => 'London', 'state' => 'England', 'country' => 'UK'],
            ['name' => 'Paris', 'state' => 'Île-de-France', 'country' => 'France'],
            ['name' => 'Tokyo', 'state' => 'Tokyo', 'country' => 'Japan'],
            ['name' => 'Sydney', 'state' => 'NSW', 'country' => 'Australia'],
        ];

        $city = fake()->randomElement($cities);
        
        return [
            'name' => fake()->randomElement([
                'Convention Center',
                'Grand Hotel',
                'Conference Hall',
                'Exhibition Center',
                'Event Space',
                'Auditorium',
                'Theater',
                'Community Center',
            ]) . ' ' . fake()->city(),
            'description' => fake()->paragraph(3),
            'address' => fake()->streetAddress(),
            'city' => $city['name'],
            'state' => $city['state'],
            'country' => $city['country'],
            'postal_code' => fake()->postcode(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'capacity' => fake()->numberBetween(50, 5000),
            'facilities' => [
                'parking' => fake()->boolean(80),
                'wifi' => fake()->boolean(90),
                'accessibility' => fake()->boolean(70),
                'catering' => fake()->boolean(60),
                'av_equipment' => fake()->boolean(85),
                'air_conditioning' => fake()->boolean(95),
            ],
            'contact_info' => [
                'phone' => fake()->phoneNumber(),
                'email' => fake()->email(),
                'website' => fake()->url(),
            ],
        ];
    }
}
