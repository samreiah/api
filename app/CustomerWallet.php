<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerWallet extends Model
{
	protected $dates 	= ['created_at', 'updated_at'];
	
	public function customer() {
		
		return $this->belongsTo('App\Customer');
		
	}
		
}
