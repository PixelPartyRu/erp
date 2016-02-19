<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyChargeCommission extends Model
{

    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
    }

    public function chargeCommission()
    {
        return $this->belongsTo('App\ChargeCommission');
    }

    public function Repayment()
    {
        return $this->belongsTo('App\Repayment');
    }
}
