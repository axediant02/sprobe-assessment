<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $filalble=['name','bio'];

    public function books(){
        return $this->belongsToMany(Book::class);
    }
}
