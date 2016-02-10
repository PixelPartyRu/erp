<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargeCommission extends Model
{
    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
    }

    public function chargeCommissionView()
    {
        return $this->hasOne('App\ChargeCommissionView');
    }

    public function dailyChargeCommission()
    {
        return $this->hasMany('App\DailyChargeCommission');
    }
}
