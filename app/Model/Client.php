<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function agreements()
    {
        return $this->hasMany('App\Model\Agreement');
    }

    public function relations()
    {
        return $this->hasMany('App\Model\Relation');
    }
}
