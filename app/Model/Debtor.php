<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Debtor extends Model
{
    public function relations()
    {
        return $this->hasMany('App\Model\Relation');
    }
}
