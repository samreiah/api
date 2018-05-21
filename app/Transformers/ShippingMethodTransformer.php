<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\ShippingMethod;

class ShippingMethodTransformer extends TransformerAbstract
{
	
    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(ShippingMethod $Shippingmethod)
  	{
  	    return [
            'shipping_method_id'    => $Shippingmethod->id,
            'shipping_method_type'  => $Shippingmethod->type,
            'is_active'             => $Shippingmethod->is_active,
  	    ];
  	}
}
