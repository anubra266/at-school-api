<?php

namespace App;

use App\ObjectiveQuestion;
use Illuminate\Database\Eloquent\Model;

class ObjectiveOption extends Model
{
    protected $guarded = [];

    public function objectivequestions(){
        return $this->belongsTo(ObjectiveQuestion::class);
    }
}
