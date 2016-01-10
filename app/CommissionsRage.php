<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommissionsRage extends Model
{
    public function commission()
    {
        return $this->belongsTo('App\Commission');
    }
}
