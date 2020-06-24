<?php

namespace App;

use App\Cbt;
use App\ObjectiveQuestion;
use Illuminate\Database\Eloquent\Model;

class ObjectiveTest extends Model 
{
    protected $guarded = [];

    public function classrooms(){
        return $this->belongsTo(Classroom::class);
    } 

    public function objectivequestions(){
        return $this->hasMany(ObjectiveQuestion::class);
    }

    public function cbts(){
        return $this->hasMany(Cbt::class);
    }
}
