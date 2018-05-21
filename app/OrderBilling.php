<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBilling extends Model
{

	protected $dates    = ['created_at', 'updated_at', 'order_created'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function order() {
		return $this->belongsTo('App\Order');
	}

	public function paymentAddress() {
		return $this->belongsTo('App\CustomerAddress', 'payment_address_id');
	}

	public function shippingAddress() {
		return $this->belongsTo('App\CustomerAddress', 'shipping_address_id');
	}
}
