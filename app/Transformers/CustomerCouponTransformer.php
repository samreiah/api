<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\CustomerCoupon;
use App\Transformers\CouponTransformer;

class CustomerCouponTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'coupon'
    ];

    protected $defaultIncludes = [
        'coupon'
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(CustomerCoupon $customerCoupon)
  	{
  	    return [
			'customer_coupon_id'	=> $customerCoupon->id,
            'customer_id'       	=> $customerCoupon->customer_id,
            'coupon_id'     		=> $customerCoupon->coupon_id,
            'credit_received'       => $customerCoupon->credit_received,
			'added_at'				=> $customerCoupon->created_at->toDateString(),
			'created_at'			=> $customerCoupon->created_at,
  	    ];
  	}
	
	public function includeCoupon(CustomerCoupon $customerCoupon)
    {
        $coupon = $customerCoupon->coupon;
		if($coupon == null) {
			return null;
		}
		
        return $this->item($coupon, new CouponTransformer);
    }
}
