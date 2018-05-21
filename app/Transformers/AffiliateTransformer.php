<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\User;
use App\Affiliate;
use App\Transformers\CustomerAddressTransformer;

class AffiliateTransformer extends TransformerAbstract
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

  	public function transform(Affiliate $affiliate)
  	{
  	    return [
            'affiliate_id'       	=> $affiliate->id,
            'user_id'           	=> $affiliate->user_id,
            'email'             	=> $affiliate->email,
            'firstname'             => $affiliate->firstname,
			'lastname'				=> $affiliate->lastname,
            'mobile'            	=> $affiliate->mobile,
            'telephone'         	=> $affiliate->telephone,
            'avatar'            	=> $affiliate->avatar,
			'company_name'			=> $affiliate->company_name,
			'slug'					=> $affiliate->slug,
			'website'				=> $affiliate->website,
			'street1'				=> $affiliate->addr_line1,
			'street2'				=> $affiliate->addr_line2,
			'city'					=> $affiliate->city,
			'postcode'				=> $affiliate->postcode,
			'affiliate_state'		=> $affiliate->affiliate_state,
			'approved'				=> $affiliate->approved,
			'contact_visible'		=> $affiliate->contact_visible,
  	    ];
  	}

    public function includeCountry(Affiliate $affiliate)
    {
        $country = $affiliate->find($affiliate->id)->country()->first();
		if($country == null) {
			return null;
		}
        return $this->item($country, new CountryTransformer);
    }
}
