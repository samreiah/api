<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\CustomerAddress;
use App\Transformers\CountryTransformer;

class CustomerAddressTransformer extends TransformerAbstract
{
    protected $availableIncludes = [

        'country'
    ];

    protected $defaultIncludes = [

        'country'
    ];

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(CustomerAddress $customeraddress)
  	{
  	    return [
			'customer_id'	=> $customeraddress->customer_id,
			'address_id'	=> $customeraddress->id,
			'addr_name'     => $customeraddress->firstname,
			'street1'       => $customeraddress->addr_line1,
			'street2'       => $customeraddress->addr_line2,
			'mobile'       	=> $customeraddress->mobile,
			'telephone'     => $customeraddress->telephone,
			'postcode'     	=> $customeraddress->postcode,
			'default'     	=> $customeraddress->default,
  	    ];
  	}

    public function includeCountry(CustomerAddress $customeraddress)
    {
		$country = $customeraddress->find($customeraddress->id)->Country()->first();
		if($country == null) {
			return null;
		}
        return $this->item($country, new CountryTransformer);
    }
}
