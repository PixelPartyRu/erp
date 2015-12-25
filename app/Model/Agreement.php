<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;

class Agreement extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Model\Client');
    }
}
