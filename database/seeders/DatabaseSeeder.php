<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Venue;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\Session;
use App\Models\Coupon;
use App\Models\Referral;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@eventmanager.com',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create organizer users
        $organizers = User::factory(5)->create([
            'email_verified_at' => now(),
        ]);
        foreach ($organizers as $organizer) {
            $organizer->assignRole('organizer');
        }

        // Create staff users
        $staff = User::factory(3)->create([
            'email_verified_at' => now(),
        ]);
        foreach ($staff as $staffUser) {
            $staffUser->assignRole('staff');
        }

        // Create regular attendee users
        $attendees = User::factory(50)->create([
            'email_verified_at' => now(),
        ]);
        foreach ($attendees as $attendee) {
            $attendee->assignRole('attendee');
        }

        // Create venues
        $venues = Venue::factory(10)->create();

        // Create events
        $events = collect();
        foreach ($organizers as $organizer) {
            $organizerEvents = Event::factory(rand(2, 5))->create([
                'organizer_id' => $organizer->id,
                'venue_id' => $venues->random()->id,
                'status' => 'published',
            ]);
            $events = $events->merge($organizerEvents);
        }

        // Create ticket types for each event
        foreach ($events as $event) {
            // General admission ticket
            $generalTicket = TicketType::factory()->create([
                'event_id' => $event->id,
                'name' => 'General Admission',
                'price' => fake()->randomFloat(2, 25, 100),
                'quantity_total' => $totalGeneral = rand(100, 500),
                'quantity_available' => rand(0, $totalGeneral),
            ]);

            // VIP ticket (if event capacity allows)
            if ($event->total_capacity > 200) {
                $vipTicket = TicketType::factory()->create([
                    'event_id' => $event->id,
                    'name' => 'VIP',
                    'price' => $generalTicket->price * 2.5,
                    'quantity_total' => $totalVip = rand(20, 100),
                    'quantity_available' => rand(0, $totalVip),
                    'benefits' => [
                        'priority_seating',
                        'complimentary_drinks',
                        'meet_and_greet',
                        'exclusive_access',
                    ],
                ]);
            }

            // Early bird ticket (for future events)
            if ($event->start_date > now()->addWeeks(4)) {
                TicketType::factory()->create([
                    'event_id' => $event->id,
                    'name' => 'Early Bird',
                    'price' => $generalTicket->price * 0.8,
                    'quantity_total' => $totalEarly = rand(50, 200),
                    'quantity_available' => rand(0, $totalEarly),
                    'sale_end' => $event->start_date->subWeeks(2),
                ]);
            }

            // Create event sessions
            Session::factory(rand(3, 8))->create([
                'event_id' => $event->id,
            ]);

            // Update event price range based on ticket types
            $ticketTypes = $event->ticketTypes;
            $event->update([
                'min_price' => $ticketTypes->min('price'),
                'max_price' => $ticketTypes->max('price'),
            ]);
        }

        // Create some orders and tickets
        $publishedEvents = $events->where('status', 'published');
        foreach ($attendees->take(30) as $attendee) {
            // Each attendee might have 1-3 orders
            $orderCount = rand(0, 3);
            
            for ($i = 0; $i < $orderCount; $i++) {
                $event = $publishedEvents->random();
                $ticketTypes = $event->ticketTypes->where('quantity_available', '>', 0);
                
                if ($ticketTypes->isEmpty()) continue;

                $order = Order::factory()->create([
                    'user_id' => $attendee->id,
                    'event_id' => $event->id,
                    'status' => fake()->randomElement(['completed', 'completed', 'completed', 'pending']),
                ]);

                // Add 1-4 different ticket types to the order
                $selectedTicketTypes = $ticketTypes->random(min(rand(1, 4), $ticketTypes->count()));
                $subtotal = 0;

                foreach ($selectedTicketTypes as $ticketType) {
                    $quantity = rand(1, min(3, $ticketType->quantity_available));
                    $unitPrice = $ticketType->price;
                    $totalPrice = $quantity * $unitPrice;
                    $subtotal += $totalPrice;

                    $orderItem = OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'ticket_type_id' => $ticketType->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                    ]);

                    // Create individual tickets
                    for ($j = 0; $j < $quantity; $j++) {
                        Ticket::factory()->create([
                            'order_id' => $order->id,
                            'order_item_id' => $orderItem->id,
                            'ticket_type_id' => $ticketType->id,
                            'event_id' => $event->id,
                            'user_id' => $attendee->id,
                            'attendee_name' => fake()->boolean(70) ? $attendee->name : fake()->name(),
                            'attendee_email' => fake()->boolean(70) ? $attendee->email : fake()->email(),
                            'status' => $order->status === 'completed' ? 'valid' : 'cancelled',
                        ]);
                    }

                    // Update ticket type availability
                    $ticketType->decrement('quantity_available', $quantity);
                }

                // Update order totals
                $order->update([
                    'subtotal' => $subtotal,
                    'total_amount' => $subtotal,
                    'payment_completed_at' => $order->status === 'completed' ? now() : null,
                ]);
            }
        }

        // Create discount coupons
        Coupon::factory(10)->create();

        // Create referral codes
        foreach ($organizers->take(3) as $organizer) {
            Referral::factory(rand(1, 3))->create([
                'user_id' => $organizer->id,
                'event_id' => $events->where('organizer_id', $organizer->id)->random()?->id,
            ]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin credentials: admin@eventmanager.com / password');
        $this->command->info('Created: ' . User::count() . ' users, ' . Event::count() . ' events, ' . Order::count() . ' orders');
    }
}
