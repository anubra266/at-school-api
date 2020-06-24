<?php

namespace App;

use App\User;
use App\Environ;
use App\TheoryTest;
use App\ObjectiveTest;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $guarded = [];

    public function environs(){
        return $this->belongsTo(Environ::class);
    }

    public function users(){
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    public function theorytests(){
        return $this->hasMany(TheoryTest::class);
    }
    public function objectivetests(){
        return $this->hasMany(ObjectiveTest::class);
    }
}
