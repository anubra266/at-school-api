<?php

namespace App;

use App\User;
use App\Environ;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['name', 'address','code'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function environs(){
        return $this->hasMany(Environ::class);
    }
}
