<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\OrderTracking;

class OrderTrackingTransformer extends TransformerAbstract
{
    

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(OrderTracking $ordertracking)
  	{
  	    return [
          'is_shipped'			  => $ordertracking->is_shipped,
          'is_delivered'      => $ordertracking->is_delvered,
          'comment'     		  => $ordertracking->comment,
          'created_at'        => $ordertracking->created_at->toDateTimeString(),
          'updated_at'				=> $ordertracking->updated_at->toDateTimeString(),
  	    ];
  	}

}
