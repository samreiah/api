<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{

	protected $dates    = ['created_at', 'updated_at'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function order() {
		return $this->belongsTo('App\Order');
	}

	public function product() {
		return $this->belongsTo('App\Product');
	}

	public function productOption() {
		return $this->belongsTo('App\ProductOption');
	}

	public function optionValue() {
		return $this->belongsTo('App\OptionValue');
	}

	public function affiliate() {
		return $this->belongsTo('App\Affiliate');
	}

	public function orderTracking() {
        return $this->hasMany('App\OrderTracking');
    }
}
