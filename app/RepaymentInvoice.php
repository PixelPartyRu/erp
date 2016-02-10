<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepaymentInvoice extends Model
{
    public function delivery()
    {
        return $this->belongsTo('App\Delivery');
    }

    public function repayment()
    {
        return $this->belongsTo('App\Repayment');
    }
}
