<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;

class LoanSeeder extends Seeder
{
    public function run()
    {
        $loans = [
            ['user_id' => 1, 'book_id' => 1, 'loan_date' => Carbon::now()->subDays(10), 'return_date' => Carbon::now()->addDays(10)],
            ['user_id' => 2, 'book_id' => 2, 'loan_date' => Carbon::now()->subDays(5), 'return_date' => Carbon::now()->addDays(5)],
            ['user_id' => 3, 'book_id' => 3, 'loan_date' => Carbon::now()->subDays(3), 'return_date' => Carbon::now()->addDays(7)],
            ['user_id' => 1, 'book_id' => 4, 'loan_date' => Carbon::now()->subDays(1), 'return_date' => Carbon::now()->addDays(14)],
            ['user_id' => 2, 'book_id' => 5, 'loan_date' => Carbon::now(), 'return_date' => Carbon::now()->addDays(10)],
        ];

        foreach ($loans as $loan) {
            Loan::create($loan);
        }
    }
}
