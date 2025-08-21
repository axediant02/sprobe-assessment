<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoanItem;

class LoanItemSeeder extends Seeder
{
    public function run()
    {
        $loanItems = [
            ['loan_id' => 1, 'book_id' => 1, 'due_date' => now()->addDays(10), 'status' => 'borrowed'],
            ['loan_id' => 2, 'book_id' => 2, 'due_date' => now()->addDays(5), 'status' => 'borrowed'],
            ['loan_id' => 3, 'book_id' => 3, 'due_date' => now()->addDays(7), 'status' => 'borrowed'],
            ['loan_id' => 4, 'book_id' => 4, 'due_date' => now()->addDays(14), 'status' => 'borrowed'],
            ['loan_id' => 5, 'book_id' => 5, 'due_date' => now()->addDays(10), 'status' => 'borrowed'],
        ];

        foreach ($loanItems as $item) {
            LoanItem::create($item);
        }
    }
}
