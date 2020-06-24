<?php

namespace App;

use App\TheoryTest;
use App\TheoryAnswer;
use Illuminate\Database\Eloquent\Model;

class TheoryQuestion extends Model
{
    protected $guarded = [];
    
    public function theorytests(){
        return $this->belongsTo(TheoryTest::class);
    }

    public function theoryanswers(){
        return $this->hasMany(TheoryAnswer::class);
    }
}
  