<?php

namespace App;

use App\User;
use App\TheoryQuestion;
use Illuminate\Database\Eloquent\Model;

class TheoryAnswer extends Model
{
    protected $guarded = [];

    public function theoryquestions(){
        return $this->belongsTo(TheoryQuestion::class);
    }

    public function user(){ 
        return $this->belongsTo(User::class);
    }
}
