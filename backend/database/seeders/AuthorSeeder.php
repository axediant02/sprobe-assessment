<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    public function run()
    {
        $authors = [
            ['name' => 'George Orwell', 'bio' => 'English novelist, essayist, journalist and critic.'],
            ['name' => 'J.K. Rowling', 'bio' => 'British author, best known for the Harry Potter series.'],
            ['name' => 'F. Scott Fitzgerald', 'bio' => 'American novelist and short story writer.'],
            ['name' => 'Jane Austen', 'bio' => 'English novelist known for her six major novels.'],
            ['name' => 'Mark Twain', 'bio' => 'American writer, humorist, entrepreneur, publisher, and lecturer.'],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }
    }
}
