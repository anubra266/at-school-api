<?php

namespace App;

use App\ObjectiveTest;
use App\ObjectiveAnswer;
use App\ObjectiveOption;
use Illuminate\Database\Eloquent\Model;

class ObjectiveQuestion extends Model
{
    protected $guarded = [];
    
    public function objectivetests(){
        return $this->belongsTo(ObjectiveTest::class);
    }
 
    public function objectiveoptions(){
        return $this->hasMany(ObjectiveOption::class);
    }
    
    public function objectiveanswers(){
        return $this->hasMany(ObjectiveAnswer::class);
    }

}
