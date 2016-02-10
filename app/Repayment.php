<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
	public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function debtor()
    {
        return $this->belongsTo('App\Debtor');
    }

    public function repaymentInvoice()
    {
        return $this->hasMany('App\RepaymentInvoice');
    }
}
