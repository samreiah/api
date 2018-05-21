<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\Coupon;

class CouponTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        
    ];

    protected $defaultIncludes = [
        
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(Coupon $coupon)
  	{
  	    return [
            'coupon_id'       	=> $coupon->id,
            'coupon_code'     	=> $coupon->coupon_code,
            'points'            => $coupon->points,
            'valid_from'      	=> $coupon->valid_from->toDateString(),
			'valid_till'		=> $coupon->valid_till->toDateString(),
            'user_group'        => $coupon->user_group,
            'user_id'  			=> $coupon->user_id,
            'valid_for'      	=> $coupon->valid_for,
            'is_redeemed'       => $coupon->is_redeemed,
            'redeemed_for'      => $coupon->redeemed_for,
  	    ];
  	}
}
