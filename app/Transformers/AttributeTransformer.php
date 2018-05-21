<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\Attribute;
//use App\Transformers\ManufacturerTransformer;

class AttributeTransformer extends TransformerAbstract
{


    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(Attribute $attribute)
  	{
  	    return [
            'attribute_id'   		=> $attribute->id,
            'attribute_group_id' 	=> $attribute->attribute_group_id,
            'attribute_name'     	=> $attribute->name,
  	    ];
  	}
}
