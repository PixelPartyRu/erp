<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function debtor()
    {
        return $this->belongsTo('App\Debtor');
    }

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }

    public function originalDocument()
    {
        return $this->belongsTo('App\OriginalDocument');
    }
    public function tariff()
    {
        return $this->belongsTo('App\Tariff');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Delivery');
    }
    public function limit()
    {
        return $this->hasOne('App\Limit');
    }

}
