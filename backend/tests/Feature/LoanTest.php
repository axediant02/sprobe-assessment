<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class LoanTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $user;
    private $token;
    private $book;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
        $this->book = Book::factory()->create();
    }

    public function test_authenticated_user_can_list_their_loans()
    {
        // Create loans for the authenticated user
        Loan::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id
        ]);

        // Create loans for another user (should not appear)
        $otherUser = User::factory()->create();
        Loan::factory()->count(2)->create([
            'user_id' => $otherUser->id,
            'book_id' => $this->book->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/loans');

        $response->assertStatus(200)
                ->assertJsonCount(3)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'user_id',
                        'book_id',
                        'loan_date',
                        'return_date',
                        'status',
                        'created_at',
                        'updated_at',
                        'user',
                        'book'
                    ]
                ]);
    }

    public function test_unauthenticated_user_cannot_list_loans()
    {
        $response = $this->getJson('/api/loans');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_loan()
    {
        $loanData = [
            'book_id' => $this->book->id,
            'loan_date' => now()->toDateString(),
            'return_date' => now()->addDays(14)->toDateString()
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/loans', $loanData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'user_id',
                    'book_id',
                    'loan_date',
                    'return_date',
                    'status',
                    'created_at',
                    'updated_at',
                    'user',
                    'book'
                ])
                ->assertJson([
                    'book_id' => $this->book->id,
                    'user_id' => $this->user->id,
                    'status' => 'ongoing'
                ]);

        $this->assertDatabaseHas('loans', [
            'book_id' => $this->book->id,
            'user_id' => $this->user->id,
            'status' => 'ongoing'
        ]);
    }

    public function test_loan_creation_automatically_sets_user_id()
    {
        $loanData = [
            'book_id' => $this->book->id,
            'loan_date' => now()->toDateString()
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/loans', $loanData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('loans', [
            'book_id' => $this->book->id,
            'user_id' => $this->user->id
        ]);
    }

    public function test_loan_creation_validates_required_fields()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/loans', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['book_id', 'loan_date']);
    }

    public function test_loan_creation_validates_book_exists()
    {
        $loanData = [
            'book_id' => 999, // Non-existent book
            'loan_date' => now()->toDateString()
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/loans', $loanData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['book_id']);
    }

    public function test_loan_creation_validates_date_format()
    {
        $loanData = [
            'book_id' => $this->book->id,
            'loan_date' => 'invalid-date'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/loans', $loanData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['loan_date']);
    }

    public function test_loan_creation_validates_return_date_after_loan_date()
    {
        $loanData = [
            'book_id' => $this->book->id,
            'loan_date' => now()->toDateString(),
            'return_date' => now()->subDays(1)->toDateString() // Before loan date
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/loans', $loanData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['return_date']);
    }

    public function test_authenticated_user_can_view_specific_loan()
    {
        $loan = Loan::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/loans/' . $loan->id);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $loan->id,
                    'user_id' => $this->user->id,
                    'book_id' => $this->book->id
                ]);
    }

    public function test_viewing_nonexistent_loan_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/loans/999');

        $response->assertStatus(404)
                ->assertJson(['message' => 'Loan not found']);
    }

    public function test_authenticated_user_can_update_loan()
    {
        $loan = Loan::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id
        ]);

        $updateData = [
            'return_date' => now()->addDays(30)->toDateString(),
            'status' => 'completed'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/loans/' . $loan->id, $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'completed'
                ]);

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'completed'
        ]);
    }

    public function test_updating_nonexistent_loan_returns_404()
    {
        $updateData = [
            'status' => 'completed'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/loans/999', $updateData);

        $response->assertStatus(404)
                ->assertJson(['message' => 'Loan not found']);
    }

    public function test_loan_update_validates_status_values()
    {
        $loan = Loan::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id
        ]);

        $updateData = [
            'status' => 'invalid-status'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/loans/' . $loan->id, $updateData);

                $response->assertStatus(422)
                ->assertJsonValidationErrors(['status']);
    }

    public function test_authenticated_user_can_delete_loan()
    {
        $loan = Loan::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson('/api/loans/' . $loan->id);

        $response->assertStatus(200)
                ->assertJson(['message' => 'Loan deleted']);

        $this->assertDatabaseMissing('loans', ['id' => $loan->id]);
    }

    public function test_deleting_nonexistent_loan_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson('/api/loans/999');

        $response->assertStatus(404)
                ->assertJson(['message' => 'Loan not found']);
    }

    public function test_authenticated_user_can_complete_loan()
    {
        $loan = Loan::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id,
            'status' => 'ongoing'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->patchJson('/api/loans/' . $loan->id . '/complete');

        $response->assertStatus(200);

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'completed'
        ]);
    }

    public function test_completing_nonexistent_loan_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->patchJson('/api/loans/999/complete');

        $response->assertStatus(404);
    }
}
