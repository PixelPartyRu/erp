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
}
