<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Event permissions
            'view events',
            'create events',
            'edit events',
            'delete events',
            'publish events',
            
            // Ticket permissions
            'view tickets',
            'create tickets',
            'edit tickets',
            'delete tickets',
            'validate tickets',
            
            // Order permissions
            'view orders',
            'create orders',
            'edit orders',
            'refund orders',
            
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Venue permissions
            'view venues',
            'create venues',
            'edit venues',
            'delete venues',
            
            // Report permissions
            'view reports',
            'export reports',
            
            // Coupon permissions
            'view coupons',
            'create coupons',
            'edit coupons',
            'delete coupons',
            
            // Referral permissions
            'view referrals',
            'create referrals',
            'edit referrals',
            'delete referrals',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin role - has all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Organizer role - can manage their own events and related data
        $organizerRole = Role::create(['name' => 'organizer']);
        $organizerRole->givePermissionTo([
            'view events', 'create events', 'edit events', 'delete events', 'publish events',
            'view tickets', 'create tickets', 'edit tickets', 'delete tickets',
            'view orders', 'create orders', 'edit orders', 'refund orders',
            'view venues', 'create venues', 'edit venues',
            'view reports', 'export reports',
            'view coupons', 'create coupons', 'edit coupons', 'delete coupons',
            'view referrals', 'create referrals', 'edit referrals', 'delete referrals',
        ]);

        // Staff role - can validate tickets and view basic data
        $staffRole = Role::create(['name' => 'staff']);
        $staffRole->givePermissionTo([
            'view events',
            'view tickets', 'validate tickets',
            'view orders',
            'view users',
        ]);

        // Attendee role - basic user permissions
        $attendeeRole = Role::create(['name' => 'attendee']);
        $attendeeRole->givePermissionTo([
            'view events',
            'view tickets',
            'create orders',
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}
