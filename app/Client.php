<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function agreements()
    {
        return $this->hasMany('App\Agreement');
    }

    public function relations()
    {
        return $this->hasMany('App\Relation');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Delivery');
    }
}
