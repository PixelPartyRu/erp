<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    public function relation()
    {
        return $this->hasOne('App\Relation');
    }
}
