<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Client');
    }
    public function relation()
    {
        return $this->HasOne('App\Relation');
    }
}
