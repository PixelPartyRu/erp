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
        return $this->hasOne('App\Contract');
    }

    public function originalDocument()
    {
        return $this->hasOne('App\OriginalDocument');
    }
}
