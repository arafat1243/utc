<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Payment extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y');
    }

    public function course(){
        return $this->hasOne(Course::class,'id','course_id');
    }
    
    public function student(){
        return $this->hasOneThrough(Student::class,StudentHasCourse::class,'id','id','st_has_co_id','student_id');
    }
}
