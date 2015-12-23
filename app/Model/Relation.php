<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Model\Client');
    }

    public function debtor()
    {
        return $this->belongsTo('App\Model\Debtor');
    }

    public function contract()
    {
        return $this->hasOne('App\Model\Contract');
    }

    public function originalDocument()
    {
        return $this->hasOne('App\Model\OriginalDocument');
    }
}
