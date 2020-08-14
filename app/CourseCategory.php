<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class CourseCategory extends Model
{
    protected $fillable = [
        'title', 'slug',
    ];
    
    public function courses(){
        return $this->hasMany(Course::class,'cat_id','id')->orderByDesc('id');
    }
    
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }
    public function getSlugAttribute($val){
        if($val == null){
            return $this->id;
        }else{
            return $val;
        }
    }
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y');
    }
}
