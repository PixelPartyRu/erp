<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyChargeCommission extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
    }

    public function chargeCommission()
    {
        return $this->belongsTo('App\ChargeCommission');
    }
}
