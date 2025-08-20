<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['name', 'email', 'phone'];

    public function loans(){
        return $this->hasMany(Loan::class);
    }
}
