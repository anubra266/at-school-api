<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObjectiveSolution extends Model
{
    protected $guarded = [];

    public function objectivequestion(){
        return $this->belongsTo(ObjectiveQuestion::class);
    }
}
