<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title','published_at'];

    public function authors()
    {
        return $this-->belongsToMany(Author::class);
    }

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }
}
