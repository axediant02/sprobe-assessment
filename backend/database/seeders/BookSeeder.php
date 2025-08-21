<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;

class BookSeeder extends Seeder
{
    public function run()
    {
        $books = [
            ['title' => '1984', 'description' => 'Dystopian social science fiction novel.'],
            ['title' => 'Harry Potter and the Sorcerer\'s Stone', 'description' => 'First book in the Harry Potter series.'],
            ['title' => 'The Great Gatsby', 'description' => 'Tragedy novel set in 1920s America.'],
            ['title' => 'Pride and Prejudice', 'description' => 'Romantic novel of manners.'],
            ['title' => 'Adventures of Huckleberry Finn', 'description' => 'American classic novel.'],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
