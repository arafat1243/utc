<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentHasCourse extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function course(){
        return $this->hasOne(Course::class,'id','course_id');
    }

    public function payment(){
        return $this->hasMany(Payment::class,'course_id','course_id');
    }
}
