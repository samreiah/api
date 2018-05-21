<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeGroup extends Model
{
    protected $dates 	= ['created_at', 'updated_at'];
	
	
	public function attributes()
    {
        return $this->hasMany('App\Attribute');
    }
}
