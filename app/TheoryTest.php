<?php

namespace App;

use App\Classroom;
use App\TheoryQuestion;
use Illuminate\Database\Eloquent\Model;

class TheoryTest extends Model
{
    protected $guarded = [];

    public function classrooms(){
        return $this->belongsTo(Classroom::class);
    }

    public function theoryquestions(){
        return $this->hasMany(TheoryQuestion::class);
    }
}
