<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Author;
use App\Models\Loan;
use App\Models\LoanItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_many_loans()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $loans = Loan::factory()->count(3)->create([
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $this->assertCount(3, $user->loans);
        $this->assertInstanceOf(Loan::class, $user->loans->first());
    }

    public function test_user_has_api_tokens()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $this->assertTrue($user->tokens()->exists());
        $this->assertInstanceOf(\Laravel\Sanctum\NewAccessToken::class, $token);
    }

    public function test_book_can_have_many_authors()
    {
        $book = Book::factory()->create();
        $authors = Author::factory()->count(3)->create();

        $book->authors()->attach($authors->pluck('id'));

        $this->assertCount(3, $book->authors);
        $this->assertInstanceOf(Author::class, $book->authors->first());
    }

    public function test_book_can_have_many_loan_items()
    {
        $book = Book::factory()->create();
        $loan = Loan::factory()->create();

        $loanItems = LoanItem::factory()->count(3)->create([
            'loan_id' => $loan->id,
            'book_id' => $book->id
        ]);

        $this->assertCount(3, $book->loanItems);
        $this->assertInstanceOf(LoanItem::class, $book->loanItems->first());
    }

    public function test_author_can_have_many_books()
    {
        $author = Author::factory()->create();
        $books = Book::factory()->count(3)->create();

        $author->books()->attach($books->pluck('id'));

        $this->assertCount(3, $author->books);
        $this->assertInstanceOf(Book::class, $author->books->first());
    }

    public function test_loan_belongs_to_user()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $this->assertInstanceOf(User::class, $loan->user);
        $this->assertEquals($user->id, $loan->user->id);
    }

    public function test_loan_belongs_to_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $this->assertInstanceOf(Book::class, $loan->book);
        $this->assertEquals($book->id, $loan->book->id);
    }

    public function test_loan_can_have_many_loan_items()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $loanItems = LoanItem::factory()->count(3)->create([
            'loan_id' => $loan->id,
            'book_id' => $book->id
        ]);

        $this->assertCount(3, $loan->loanItems);
        $this->assertInstanceOf(LoanItem::class, $loan->loanItems->first());
    }

    public function test_loan_item_belongs_to_loan()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $loanItem = LoanItem::factory()->create([
            'loan_id' => $loan->id,
            'book_id' => $book->id
        ]);

        $this->assertInstanceOf(Loan::class, $loanItem->loan);
        $this->assertEquals($loan->id, $loanItem->loan->id);
    }

    public function test_loan_item_belongs_to_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $loanItem = LoanItem::factory()->create([
            'loan_id' => $loan->id,
            'book_id' => $book->id
        ]);

        $this->assertInstanceOf(Book::class, $loanItem->book);
        $this->assertEquals($book->id, $loanItem->book->id);
    }

    public function test_user_fillable_fields()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123'
        ];

        $user = User::create($userData);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_book_fillable_fields()
    {
        $bookData = [
            'title' => 'Test Book',
            'description' => 'A test book',
            'published_at' => '2023-01-01'
        ];

        $book = Book::create($bookData);

        $this->assertEquals('Test Book', $book->title);
        $this->assertEquals('A test book', $book->description);
        $this->assertEquals('2023-01-01', $book->published_at);
    }

    public function test_author_fillable_fields()
    {
        $authorData = [
            'name' => 'Jane Doe',
            'bio' => 'A prolific author'
        ];

        $author = Author::create($authorData);

        $this->assertEquals('Jane Doe', $author->name);
        $this->assertEquals('A prolific author', $author->bio);
    }

    public function test_loan_fillable_fields()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $loanData = [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => '2023-01-01',
            'return_date' => '2023-01-15',
            'status' => 'ongoing'
        ];

        $loan = Loan::create($loanData);

        $this->assertEquals($user->id, $loan->user_id);
        $this->assertEquals($book->id, $loan->book_id);
        $this->assertEquals('2023-01-01', $loan->loan_date);
        $this->assertEquals('2023-01-15', $loan->return_date);
        $this->assertEquals('ongoing', $loan->status);
    }

    public function test_loan_item_fillable_fields()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $loanItemData = [
            'loan_id' => $loan->id,
            'book_id' => $book->id,
            'due_date' => '2023-01-15',
            'return_date' => null,
            'status' => 'borrowed'
        ];

        $loanItem = LoanItem::create($loanItemData);

        $this->assertEquals($loan->id, $loanItem->loan_id);
        $this->assertEquals($book->id, $loanItem->book_id);
        $this->assertEquals('2023-01-15', $loanItem->due_date);
        $this->assertNull($loanItem->return_date);
        $this->assertEquals('borrowed', $loanItem->status);
    }

    public function test_many_to_many_relationship_between_books_and_authors()
    {
        $book = Book::factory()->create();
        $author1 = Author::factory()->create();
        $author2 = Author::factory()->create();

        $book->authors()->attach([$author1->id, $author2->id]);

        $this->assertCount(2, $book->authors);
        $this->assertTrue($book->authors->contains($author1));
        $this->assertTrue($book->authors->contains($author2));

        // Test reverse relationship
        $this->assertCount(1, $author1->books);
        $this->assertCount(1, $author2->books);
        $this->assertTrue($author1->books->contains($book));
        $this->assertTrue($author2->books->contains($book));
    }

    public function test_cascade_delete_relationships()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $loanItem = LoanItem::factory()->create([
            'loan_id' => $loan->id,
            'book_id' => $book->id
        ]);

        // Delete the loan
        $loan->delete();

        // Loan item should be deleted due to cascade
        $this->assertDatabaseMissing('loan_items', ['id' => $loanItem->id]);
        $this->assertDatabaseMissing('loans', ['id' => $loan->id]);
    }

    public function test_user_password_is_hashed()
    {
        $user = User::factory()->create([
            'password' => 'plaintextpassword'
        ]);

        $this->assertNotEquals('plaintextpassword', $user->password);
        $this->assertTrue(Hash::check('plaintextpassword', $user->password));
    }
}
