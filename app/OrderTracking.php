<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{

	protected $dates 	= ['created_at', 'updated_at'];

	/**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;


    public function order() {
		return $this->belongsTo('App\Order');
	}

	public function orderProduct() {
		return $this->belongsTo('App\OrderProduct');
	}
}
