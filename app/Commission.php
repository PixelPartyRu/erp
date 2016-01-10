<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    public function tariff()
    {
        return $this->belongsTo('App\Tariff');
    }
    public function commissionsRages()
    {
        return $this->hasMany('App\CommissionsRage');
    }
}
