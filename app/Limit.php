<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Limit extends Model
{	
	protected $fillable = array('relation_id', 'value');
    public function relation()
    {
        return $this->belongsTo('App\Relation');
        
    }
}
