<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    public function relations()
    {
        return $this->hasMany('App\Relation');
    }
}
