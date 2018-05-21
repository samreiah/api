<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\Customer;
//use App\Transformers\ManufacturerTransformer;

class CustomerdetailsTransformer extends TransformerAbstract
{


    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(Customer $customer)
  	{
  	    return [
            'first_name'   		=> $customer->firstname,
            'last_name' 		=> $customer->lastname,
  	    ];
  	}
}
