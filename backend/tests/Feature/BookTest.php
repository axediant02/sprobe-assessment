<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class BookTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_authenticated_user_can_list_all_books()
    {
        Book::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/books');

        $response->assertStatus(200)
                ->assertJsonCount(3)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'published_at',
                        'created_at',
                        'updated_at',
                        'authors'
                    ]
                ]);
    }

    public function test_unauthenticated_user_cannot_list_books()
    {
        $response = $this->getJson('/api/books');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_book()
    {
        $bookData = [
            'title' => 'Test Book',
            'description' => 'A test book description',
            'published_at' => '2023-01-01'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/books', $bookData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'title',
                    'description',
                    'published_at',
                    'created_at',
                    'updated_at',
                    'authors'
                ])
                ->assertJson([
                    'title' => 'Test Book',
                    'description' => 'A test book description',
                    'published_at' => '2023-01-01'
                ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'description' => 'A test book description'
        ]);
    }

    public function test_book_creation_automatically_assigns_user_as_author()
    {
        $bookData = [
            'title' => 'Test Book',
            'description' => 'A test book description'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/books', $bookData);

        $response->assertStatus(201);

        $book = Book::where('title', 'Test Book')->first();
        $this->assertNotNull($book);

        $author = Author::where('name', $this->user->name)->first();
        $this->assertNotNull($author);

        $this->assertTrue($book->authors->contains($author));
    }

    public function test_book_creation_uses_current_date_if_published_at_not_provided()
    {
        $bookData = [
            'title' => 'Test Book',
            'description' => 'A test book description'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/books', $bookData);

        $response->assertStatus(201);

        $book = Book::where('title', 'Test Book')->first();
        $this->assertEquals(now()->toDateString(), $book->published_at);
    }

    public function test_book_creation_validates_required_fields()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/books', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title']);
    }

    public function test_book_creation_validates_title_length()
    {
        $bookData = [
            'title' => str_repeat('a', 256), // Exceeds 255 characters
            'description' => 'A test book description'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/books', $bookData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title']);
    }

    public function test_book_creation_validates_published_at_format()
    {
        $bookData = [
            'title' => 'Test Book',
            'description' => 'A test book description',
            'published_at' => 'invalid-date'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/books', $bookData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['published_at']);
    }

    public function test_authenticated_user_can_view_specific_book()
    {
        $book = Book::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/books/' . $book->id);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $book->id,
                    'title' => $book->title,
                    'description' => $book->description
                ]);
    }

    public function test_viewing_nonexistent_book_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/books/999');

        $response->assertStatus(404)
                ->assertJson(['message' => 'Book not found']);
    }

    public function test_authenticated_user_can_update_book()
    {
        $book = Book::factory()->create();

        $updateData = [
            'title' => 'Updated Book Title',
            'description' => 'Updated description'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/books/' . $book->id, $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'title' => 'Updated Book Title',
                    'description' => 'Updated description'
                ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Book Title',
            'description' => 'Updated description'
        ]);
    }

    public function test_updating_nonexistent_book_returns_404()
    {
        $updateData = [
            'title' => 'Updated Book Title'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/books/999', $updateData);

        $response->assertStatus(404)
                ->assertJson(['message' => 'Book not found']);
    }

    public function test_book_update_validates_title_length()
    {
        $book = Book::factory()->create();

        $updateData = [
            'title' => str_repeat('a', 256)
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/books/' . $book->id, $updateData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title']);
    }

    public function test_authenticated_user_can_delete_book()
    {
        $book = Book::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson('/api/books/' . $book->id);

        $response->assertStatus(200)
                ->assertJson(['message' => 'Book deleted']);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_deleting_nonexistent_book_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson('/api/books/999');

        $response->assertStatus(404)
                ->assertJson(['message' => 'Book not found']);
    }
}
