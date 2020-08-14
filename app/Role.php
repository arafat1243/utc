<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d-m-Y');
    }
    public function getPermissionsAttribute($value)
    {
        $permissions = explode(',',$value);
        return $permissions;
    }
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
