<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];
    public function category(){
        return $this->belongsTo(ClientCategory::class,'cat_id');
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
