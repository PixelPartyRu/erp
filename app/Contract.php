<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    public function relation()
    {
        return $this->belongsTo('App\Relation');
    }
}
