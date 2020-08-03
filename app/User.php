<?php

namespace App;

use App\Cbt;
use App\Role;
use App\Environ;
use App\Classroom;
use App\Organization;
use App\TheoryAnswer;
use App\TheoryResult;
use App\ObjectiveAnswer;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getImageAttribute()
    {
        return $this->profile_image;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }
    public function environs()
    {
        return $this->hasMany(Environ::class);
    }
    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class)->withTimestamps();
    }
    public function theoryanswers()
    {
        return $this->hasMany(TheoryAnswer::class);
    }
    public function objectiveanswers(){
        return $this->hasMany(ObjectiveAnswer::class);
    }
    public function theoryresults(){
        return $this->hasMany(TheoryResult::class);
    }
    public function cbts(){
        return $this->hasMany(Cbt::class);
    }
}
