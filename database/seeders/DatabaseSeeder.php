<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = [
            [
                'name' => 'Admin Demo',
                'email' => 'admin@example.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Warehouse Manager Demo',
                'email' => 'manager@example.com',
                'role' => 'warehouse_manager',
            ],
            [
                'name' => 'Customer Demo',
                'email' => 'customer@example.com',
                'role' => 'customer',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        // Custom domain data
        $this->call([
            CategorySeeder::class,
            SupplierSeeder::class,
            TagSeeder::class,
            ProductSeeder::class,
            LegacyCategorySeeder::class,
            ProfileSeeder::class,
        ]);
    }
}
