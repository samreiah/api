<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $dates    = ['created_at', 'updated_at', 'order_created'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;


    public function orderProducts() {
    	return $this->hasMany('App\OrderProduct');
    }

    public function shippingMethod() {
    	return $this->belongsTo('App\ShippingMethod');
    }

    public function paymentMethod() {
    	return $this->belongsTo('App\PaymentMethod');
    }

    public function orderStatus() {
    	return $this->belongsTo('App\OrderStatus');
    }

    public function orderBilling() {
        return $this->hasOne('App\OrderBilling');
    }

    public function orderTracking() {
        return $this->hasMany('App\OrderTracking');
    }
}
