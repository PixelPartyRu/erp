<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OriginalDocument extends Model
{
    public function relation()
    {
        return $this->belongsTo('App\Model\Relation');
    }
}
