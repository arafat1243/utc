<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->hasOne(User::class);
    }
}
