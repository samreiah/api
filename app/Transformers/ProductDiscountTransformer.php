<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\ProductDiscount;


class ProductDiscountTransformer extends TransformerAbstract
{

    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(ProductDiscount $productDiscount)
  	{
  	    return [
            'price'       		=> $productDiscount->price,
  	    ];
  	}

}
