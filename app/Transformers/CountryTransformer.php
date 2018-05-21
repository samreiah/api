<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\Country;

class CountryTransformer extends TransformerAbstract
{

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(Country $country)
  	{
  	    return [
			'country_id'	=> $country->id,
            'name'          => $country->name,
			'iso_2'			=> $country->iso_2
  	    ];
  	}

}
