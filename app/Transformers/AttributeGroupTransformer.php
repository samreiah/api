<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use App\AttributeGroup;

class AttributeGroupTransformer extends TransformerAbstract
{
    
    public function __construct($includes = [])
    {
        $this->defaultIncludes = array_merge($this->defaultIncludes, $includes);
    }

  	public function transform(AttributeGroup $attribute_group)
  	{
  	    return [
            'name'       			=> $attribute_group->id,
            'description'           => $attribute_group->user_id,
            'sort_order'            => $attribute_group->email,
  	    ];
  	}
}
