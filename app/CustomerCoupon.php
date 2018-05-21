<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerCoupon extends Model
{
	protected $dates 	= ['created_at', 'updated_at'];
	
	public function customer() {
		return $this->belongsTo('App\Customer');
	}
	
	public function coupon() {
		return $this->belongsTo('App\Coupon');
	}
		
}
