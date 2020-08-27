<?php

namespace App;

use App\TheoryTest;
use Illuminate\Database\Eloquent\Model;

class TheorySolution extends Model
{
    protected $guarded = [];

    public function theorytest(){
        return $this->belongsTo(TheoryTest::class);
    }
}
