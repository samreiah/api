<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
	protected $dates 	= ['created_at', 'updated_at', 'valid_from', 'valid_till'];
		
}
