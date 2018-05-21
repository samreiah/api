<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\ShippingRule;

class ShippingRuleTransformer extends TransformerAbstract
{
	
    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(ShippingRule $Shippingrule)
  	{
  	    return [
            'shipping_rule_id'      => $Shippingrule->id,
            'order_min_value'       => $Shippingrule->order_min_value,
            'shipping_charges'      => $Shippingrule->shipping_charges,
            'is_active'             => $Shippingrule->is_active,
  	    ];
  	}
}
