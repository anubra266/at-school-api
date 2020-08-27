<?php

namespace App;

use App\User;
use App\TheoryTest;
use Illuminate\Database\Eloquent\Model;

class TheoryResult extends Model
{
    protected $guarded = [];

    public function theorytest(){
        return $this->belongsTo(TheoryTest::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
