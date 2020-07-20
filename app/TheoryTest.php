<?php

namespace App;

use App\Classroom;
use App\TheoryResult;
use App\TheoryQuestion;
use App\TheorySolution;
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

    public function theoryresults(){
        return $this->hasMany(TheoryResult::class);
    }

    public function theorysolution(){
        return $this->hasMany(TheorySolution::class);
    }
}
