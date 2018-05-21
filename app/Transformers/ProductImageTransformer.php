<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\ProductImage;
//use App\Transformers\ManufacturerTransformer;

class ProductImageTransformer extends TransformerAbstract
{


    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(ProductImage $image)
  	{
  	    return [
            'image_id'   		=> $image->id,
            'image' 			=> $image->image,
  	    ];
  	}
}
