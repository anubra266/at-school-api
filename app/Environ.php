<?php

namespace App;

use App\Classroom;
use App\Organization;
use Illuminate\Database\Eloquent\Model;

class Environ extends Model
{
    protected $guarded = [];

    public function organizations(){
        return $this->belongsTo(Organization::class);
    }
    public function classrooms(){
        return $this->hasMany(Classroom::class);
    }
}
