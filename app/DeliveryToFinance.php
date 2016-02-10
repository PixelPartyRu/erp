<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryToFinance extends Model
{
    public function finance()
    {
        return $this->belongsTo('App\Finance');
    }

    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
    }
}
