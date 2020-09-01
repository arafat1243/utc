<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }


    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y');
    }

    
    public function courses(){
        return $this->belongsToMany(StudentHasCourse::class,Student::class,'id','id','id','student_id');
    }
}
