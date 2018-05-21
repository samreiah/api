<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\User;
use App\Customer;
use App\Transformers\CustomerAddressTransformer;

class CustomerTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'address'
    ];

    protected $defaultIncludes = [
        'address'
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(Customer $customer)
  	{
  	    return [
            'customer_id'       => $customer->id,
            'user_id'           => $customer->user_id,
            'email'             => $customer->email,
            'firstname'         => $customer->firstname,
			'lastname'			=> $customer->lastname,
            'mobile'            => $customer->mobile,
            'telephone'         => $customer->telephone,
            'avatar'            => $customer->avatar,
            'cart'              => $customer->cart,
            'wishlist'          => $customer->wish_list,
            'credits'           => $customer->total_credits,
            'flags'  => [
                'newsletter'    => (boolean)$customer->newsletter,
                'active'        => (boolean)$customer->active,
            ],
  	    ];
  	}

    public function includeAddress(Customer $customer)
    {
        $address = $customer->addresses->first();
		if($address == null) {
			return null;
		}
        return $this->item($address, new CustomerAddressTransformer);
    }
}
