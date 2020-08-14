<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class ClientCategory extends Model
{

    protected $fillable = [
        'title', 'slug',
    ];
    public function clients()
    {
        return $this->hasMany(Client::class,'cat_id','id')->orderByDesc('id');
    }

   public function setSlugAttribute($value){
       $this->attributes['slug'] = Str::slug($value);
    }
    public function getSlugAttribute($val)
    {
        if($val){
            return $val;
        }else{
            return $this->id;
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
