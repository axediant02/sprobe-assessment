<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class AuthorTest extends TestCase
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

    public function test_authenticated_user_can_list_all_authors()
    {
        Author::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/authors');

        $response->assertStatus(200)
                ->assertJsonCount(3)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'name',
                        'bio',
                        'created_at',
                        'updated_at'
                    ]
                ]);
    }

    public function test_unauthenticated_user_cannot_list_authors()
    {
        $response = $this->getJson('/api/authors');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_author()
    {
        $authorData = [
            'name' => 'Jane Doe',
            'bio' => 'A prolific writer with many published works.'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/authors', $authorData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'name',
                    'bio',
                    'created_at',
                    'updated_at'
                ])
                ->assertJson([
                    'name' => 'Jane Doe',
                    'bio' => 'A prolific writer with many published works.'
                ]);

        $this->assertDatabaseHas('authors', [
            'name' => 'Jane Doe',
            'bio' => 'A prolific writer with many published works.'
        ]);
    }

    public function test_author_creation_validates_required_fields()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/authors', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }

    public function test_author_creation_validates_name_length()
    {
        $authorData = [
            'name' => str_repeat('a', 256), // Exceeds 255 characters
            'bio' => 'A bio'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/authors', $authorData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }

    public function test_author_creation_without_bio_is_valid()
    {
        $authorData = [
            'name' => 'Jane Doe'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/authors', $authorData);

        $response->assertStatus(201)
                ->assertJson([
                    'name' => 'Jane Doe'
                ]);
    }

    public function test_authenticated_user_can_view_specific_author()
    {
        $author = Author::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/authors/' . $author->id);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $author->id,
                    'name' => $author->name,
                    'bio' => $author->bio
                ]);
    }

    public function test_viewing_nonexistent_author_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/authors/999');

        $response->assertStatus(404)
                ->assertJson(['message' => 'Author not found']);
    }

    public function test_authenticated_user_can_update_author()
    {
        $author = Author::factory()->create();

        $updateData = [
            'name' => 'Updated Author Name',
            'bio' => 'Updated bio information'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/authors/' . $author->id, $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'name' => 'Updated Author Name',
                    'bio' => 'Updated bio information'
                ]);

        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'name' => 'Updated Author Name',
            'bio' => 'Updated bio information'
        ]);
    }

    public function test_updating_nonexistent_author_returns_404()
    {
        $updateData = [
            'name' => 'Updated Author Name'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/authors/999', $updateData);

        $response->assertStatus(404)
                ->assertJson(['message' => 'Author not found']);
    }

    public function test_author_update_validates_name_length()
    {
        $author = Author::factory()->create();

        $updateData = [
            'name' => str_repeat('a', 256)
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/authors/' . $author->id, $updateData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }

    public function test_author_update_can_update_only_name()
    {
        $author = Author::factory()->create(['bio' => 'Original bio']);

        $updateData = [
            'name' => 'Updated Author Name'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/authors/' . $author->id, $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'name' => 'Updated Author Name',
                    'bio' => 'Original bio'
                ]);
    }

    public function test_authenticated_user_can_delete_author()
    {
        $author = Author::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson('/api/authors/' . $author->id);

        $response->assertStatus(200)
                ->assertJson(['message' => 'Author deleted']);

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }

    public function test_deleting_nonexistent_author_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson('/api/authors/999');

        $response->assertStatus(404)
                ->assertJson(['message' => 'Author not found']);
    }

    public function test_authenticated_user_can_view_author_books()
    {
        $author = Author::factory()->create();
        $books = Book::factory()->count(3)->create();

        // Attach books to author
        $author->books()->attach($books->pluck('id'));

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/authors/' . $author->id . '/books');

        $response->assertStatus(200)
                ->assertJsonCount(3);
    }

    public function test_viewing_books_for_nonexistent_author_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/authors/999/books');

        $response->assertStatus(404);
    }
}
