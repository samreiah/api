<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $dates 	= ['created_at', 'updated_at'];
	
	
	public function attribute_group()
    {
        return $this->belongsTo('App\AttributeGroup');
    }
}
