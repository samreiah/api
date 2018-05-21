<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilterGroup extends Model
{
	
	protected $dates 	= ['created_at', 'updated_at'];
	
	public $timestamps 	= true;
	
	public function filters() {
		return $this->hasMany('App\Filter');
	}
}
