<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Agreement;

class Client extends Model
{
    public function agreements()
    {
        return $this->hasMany('Model\Agreement');
    }

    public function relations()
    {
        return $this->hasMany('App\Model\Relation');
    }
}
