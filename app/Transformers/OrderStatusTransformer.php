<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\OrderStatus;

class OrderStatusTransformer extends TransformerAbstract
{
    
    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(OrderStatus $orderstatus)
  	{
  	    return [
          'order_status_id'			=> $orderstatus->id,
          'status_type'       	=> $orderstatus->type,
  	    ];
  	}

}
