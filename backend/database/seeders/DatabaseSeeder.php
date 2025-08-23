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
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        User::factory(4)->create();

        $this->call([
            AuthorSeeder::class,
            BookSeeder::class,
            LoanSeeder::class,
            LoanItemSeeder::class,
        ]);
    }
}
