<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class LoanItemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $user;
    private $token;
    private $book;
    private $loan;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
        $this->book = Book::factory()->create();
        $this->loan = Loan::factory()->create([
            'user_id' => $this->user->id,
            'book_id' => $this->book->id
        ]);
    }

    public function test_authenticated_user_can_list_all_loan_items()
    {
        LoanItem::factory()->count(3)->create([
            'loan_id' => $this->loan->id,
            'book_id' => $this->book->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/loan-items');

        $response->assertStatus(200)
                ->assertJsonCount(3)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'loan_id',
                        'book_id',
                        'due_date',
                        'return_date',
                        'status',
                        'created_at',
                        'updated_at'
                    ]
                ]);
    }

    public function test_unauthenticated_user_cannot_list_loan_items()
    {
        $response = $this->getJson('/api/loan-items');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_loan_item()
    {
        $loanItemData = [
            'loan_id' => $this->loan->id,
            'book_id' => $this->book->id,
            'due_date' => now()->addDays(14)->toDateString()
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/loan-items', $loanItemData);

        $response->assertStatus(201)
                ->assertJsonStructure([
            'id',
            'loan_id',
            'book_id',
            'due_date',
            'status',
            'created_at',
            'updated_at'
        ])
                ->assertJson([
                    'loan_id' => $this->loan->id,
                    'book_id' => $this->book->id,
                    'status' => 'borrowed'
                ]);

        $this->assertDatabaseHas('loan_items', [
            'loan_id' => $this->loan->id,
            'book_id' => $this->book->id,
            'status' => 'borrowed'
        ]);
    }

    public function test_loan_item_creation_validates_required_fields()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/loan-items', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['loan_id', 'book_id', 'due_date']);
    }

    public function test_loan_item_creation_validates_loan_exists()
    {
        $loanItemData = [
            'loan_id' => 999, // Non-existent loan
            'book_id' => $this->book->id,
            'due_date' => now()->addDays(14)->toDateString()
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/loan-items', $loanItemData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['loan_id']);
    }

    public function test_loan_item_creation_validates_book_exists()
    {
        $loanItemData = [
            'loan_id' => $this->loan->id,
            'book_id' => 999, // Non-existent book
            'due_date' => now()->addDays(14)->toDateString()
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/loan-items', $loanItemData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['book_id']);
    }

    public function test_loan_item_creation_validates_date_format()
    {
        $loanItemData = [
            'loan_id' => $this->loan->id,
            'book_id' => $this->book->id,
            'due_date' => 'invalid-date'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/loan-items', $loanItemData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['due_date']);
    }

    public function test_loan_item_creation_sets_default_status()
    {
        $loanItemData = [
            'loan_id' => $this->loan->id,
            'book_id' => $this->book->id,
            'due_date' => now()->addDays(14)->toDateString()
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->postJson('/api/loan-items', $loanItemData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('loan_items', [
            'loan_id' => $this->loan->id,
            'book_id' => $this->book->id,
            'status' => 'borrowed'
        ]);
    }

    public function test_authenticated_user_can_view_specific_loan_item()
    {
        $loanItem = LoanItem::factory()->create([
            'loan_id' => $this->loan->id,
            'book_id' => $this->book->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/loan-items/' . $loanItem->id);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $loanItem->id,
                    'loan_id' => $this->loan->id,
                    'book_id' => $this->book->id
                ]);
    }

    public function test_viewing_nonexistent_loan_item_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/loan-items/999');

        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_update_loan_item()
    {
        $loanItem = LoanItem::factory()->create([
            'loan_id' => $this->loan->id,
            'book_id' => $this->book->id
        ]);

        $updateData = [
            'due_date' => now()->addDays(30)->toDateString(),
            'status' => 'returned'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/loan-items/' . $loanItem->id, $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'status' => 'returned'
                ]);

        $this->assertDatabaseHas('loan_items', [
            'id' => $loanItem->id,
            'status' => 'returned'
        ]);
    }

    public function test_updating_nonexistent_loan_item_returns_404()
    {
        $updateData = [
            'status' => 'returned'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/loan-items/999', $updateData);

        $response->assertStatus(404);
    }

    public function test_loan_item_update_validates_status_values()
    {
        $loanItem = LoanItem::factory()->create([
            'loan_id' => $this->loan->id,
            'book_id' => $this->book->id
        ]);

        $updateData = [
            'status' => 'invalid-status'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->putJson('/api/loan-items/' . $loanItem->id, $updateData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['status']);
    }

    public function test_authenticated_user_can_delete_loan_item()
    {
        $loanItem = LoanItem::factory()->create([
            'loan_id' => $this->loan->id,
            'book_id' => $this->book->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson('/api/loan-items/' . $loanItem->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('loan_items', ['id' => $loanItem->id]);
    }

    public function test_deleting_nonexistent_loan_item_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson('/api/loan-items/999');

        $response->assertStatus(404);
    }

    public function test_authenticated_user_can_return_loan_item()
    {
        $loanItem = LoanItem::factory()->create([
            'loan_id' => $this->loan->id,
            'book_id' => $this->book->id,
            'status' => 'borrowed'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->patchJson('/api/loan-items/' . $loanItem->id . '/return');

        $response->assertStatus(200);

        $this->assertDatabaseHas('loan_items', [
            'id' => $loanItem->id,
            'status' => 'returned'
        ]);
    }

    public function test_returning_nonexistent_loan_item_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->patchJson('/api/loan-items/999/return');

        $response->assertStatus(404);
    }

    public function test_returning_loan_item_sets_return_date()
    {
        $loanItem = LoanItem::factory()->create([
            'loan_id' => $this->loan->id,
            'book_id' => $this->book->id,
            'status' => 'borrowed'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->patchJson('/api/loan-items/' . $loanItem->id . '/return');

        $response->assertStatus(200);

        $this->assertDatabaseHas('loan_items', [
            'id' => $loanItem->id,
            'status' => 'returned'
        ]);

        // Check that return_date is set (but don't check exact time)
        $loanItem->refresh();
        $this->assertNotNull($loanItem->return_date);
        $this->assertEquals('returned', $loanItem->status);
    }
}
