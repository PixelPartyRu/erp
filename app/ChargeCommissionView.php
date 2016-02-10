<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargeCommissionView extends Model
{
    public function chargeCommission()
    {
        return $this->belongsTo('App\ChargeCommission');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function debtor()
    {
        return $this->belongsTo('App\Debtor');
    }
}
