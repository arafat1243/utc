<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Service extends Model
{
    protected $guarded = [];
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
