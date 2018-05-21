<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
	
	protected $dates 	= ['created_at', 'updated_at'];
	
	public function Addresses()	{
		
	  return $this->hasMany('App\CustomerAddress');
	}
	
	public function Affiliates() {
		
		return $this->hasMany('App\Affiliate');
	}
}
