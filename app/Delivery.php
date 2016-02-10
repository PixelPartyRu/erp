<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function debtor()
    {
        return $this->belongsTo('App\Debtor');
    }

    public function finance()
    {
        return $this->belongsTo('App\Finance');
    }

    public function relation()
    {
        return $this->belongsTo('App\Relation');
    }

    public function chargeCommission()
    {
        return $this->hasOne('App\ChargeCommission');
    }

    public function repaymentInvoice()
    {
        return $this->hasMany('App\RepaymentInvoice');
    }

    public function deliveryToFinance()
    {
        return $this->hasMany('App\DeliveryToFinance');
    }
    
    public function dailyChargeCommission()
    {
        return $this->hasMany('App\DailyChargeCommission');
    }
    
}
