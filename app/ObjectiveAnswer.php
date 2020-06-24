<?php

namespace App;

use App\Cbt;
use App\User;
use App\ObjectiveQuestion;
use Illuminate\Database\Eloquent\Model;

class ObjectiveAnswer extends Model
{
    protected $guarded = [];
    
     public function users(){
         return $this->belongsTo(User::class);
     }

     public function objectivequestions(){
         return $this->belongsTo(ObjectiveQuestion::class);
     }
     
     public function cbts(){
         return $this->belongsTo(Cbt::class);
     }
}
