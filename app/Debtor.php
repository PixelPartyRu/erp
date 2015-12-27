<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Debtor extends Model
{
    public function relations()
    {
        return $this->hasMany('App\Relation');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Delivery');
    }
}
