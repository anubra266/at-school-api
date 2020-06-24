<?php

namespace App;

use App\ObjectiveTest;
use App\ObjectiveAnswer;
use Illuminate\Database\Eloquent\Model;

class Cbt extends Model
{
    protected $guarded = [];

    public function objectivetests(){
        return $this->belongsTo(ObjectiveTest::class);
    }

    public function objectiveanswers(){
        return $this->hasMany(ObjectiveAnswer::class);
    }
}
 