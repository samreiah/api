<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
	protected $dates 	= ['created_at', 'updated_at'];

	public $timestamps = true;
	
	public function customer() {
		
		return $this->belongsTo('App\Customer');
	}

	public function product() {

		return $this->belongsTo('App\Product');
	}
	
}
