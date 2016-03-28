<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    public function relations()
    {
        return $this->HasMany('App\Relation');
    }
    public function bill()
    {
        return $this->HasOne('App\Bill');
    }
}
