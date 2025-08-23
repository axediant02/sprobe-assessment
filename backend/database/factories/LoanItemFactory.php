<?php

namespace Database\Factories;

use App\Models\LoanItem;
use App\Models\Loan;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoanItem>
 */
class LoanItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'loan_id' => Loan::factory(),
            'book_id' => Book::factory(),
            'due_date' => $this->faker->date(),
            'return_date' => $this->faker->optional()->date(),
            'status' => $this->faker->randomElement(['borrowed', 'returned']),
        ];
    }
}
