<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    public function deliveries()
    {
        return $this->hasMany('App\Delivery');
    }

    public function deliveryToFinance()
    {
        return $this->hasMany('App\DeliveryToFinance');
    }
}
