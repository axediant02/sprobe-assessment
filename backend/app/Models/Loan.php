<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'loan_date', 'return_date', 'status'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }
}
