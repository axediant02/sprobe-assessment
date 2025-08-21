<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Author;
use App\Models\Book;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a default user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        User::factory(4)->create();

        // Call individual seeders
        $this->call([
            AuthorSeeder::class,
            BookSeeder::class,
            LoanSeeder::class,
            LoanItemSeeder::class,
        ]);
    }
}
