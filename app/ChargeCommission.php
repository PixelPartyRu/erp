<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargeCommission extends Model
{
    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
    }
}
