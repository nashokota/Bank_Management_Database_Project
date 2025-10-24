<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Banking Admin',
            'email' => 'admin@bankingsystem.com',
        ]);

        // Run banking system seeders
        $this->call([
            BranchSeeder::class,
            CustomerSeeder::class,
            AccountSeeder::class,
        ]);
    }
}
